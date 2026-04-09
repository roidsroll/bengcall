<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('q', ''));

        $categories = Category::query()
            ->withCount('products')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($inner) use ($search) {
                    $inner
                        ->where('code', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('admin.categories.index', [
            'categories' => $categories,
            'search' => $search,
        ]);
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:255', 'unique:categories,code'],
            'name' => ['required', 'string', 'max:255'],
        ]);

        $slug = $this->generateUniqueSlug($validated['name']);

        Category::create([
            'code' => strtoupper(trim($validated['code'])),
            'name' => $validated['name'],
            'slug' => $slug,
        ]);

        return redirect()->route('admin.categories.index')->with('status', 'Category berhasil dibuat.');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', [
            'category' => $category,
        ]);
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validate([
            'code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'code')->ignore($category->id),
            ],
            'name' => ['required', 'string', 'max:255'],
        ]);

        $slug = $this->generateUniqueSlug($validated['name'], $category->id);
        $categoryCode = strtoupper(trim($validated['code']));
        $codeChanged = $category->code !== $categoryCode;

        $category->update([
            'code' => $categoryCode,
            'name' => $validated['name'],
            'slug' => $slug,
        ]);

        if ($codeChanged) {
            $this->syncProductCodeParts($category);
        }

        return redirect()->route('admin.categories.index')->with('status', 'Category berhasil diupdate.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        $category->loadCount('products');

        if ($category->products_count > 0) {
            return back()->with('status', 'Category tidak bisa dihapus karena masih dipakai product.');
        }

        $category->delete();

        return redirect()->route('admin.categories.index')->with('status', 'Category berhasil dihapus.');
    }

    private function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);
        $base = $base !== '' ? $base : 'category';

        $slug = $base;
        $counter = 2;

        while (
            Category::query()
                ->when($ignoreId, fn ($q) => $q->whereKeyNot($ignoreId))
                ->where('slug', $slug)
                ->exists()
        ) {
            $slug = "{$base}-{$counter}";
            $counter++;
        }

        return $slug;
    }

    private function syncProductCodeParts(Category $category): void
    {
        $categoryCode = strtoupper(trim((string) $category->code));

        if ($categoryCode === '') {
            return;
        }

        $counter = 1;

        foreach ($category->products()->orderBy('id')->get() as $product) {
            $product->update([
                'code_parts' => $categoryCode . '-' . str_pad((string) $counter, 3, '0', STR_PAD_LEFT),
            ]);

            $counter++;
        }
    }
}
