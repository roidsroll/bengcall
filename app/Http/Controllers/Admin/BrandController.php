<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('q', ''));

        $brands = Brand::query()
            ->withCount('products')
            ->when($search !== '', function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('admin.brands.index', [
            'brands' => $brands,
            'search' => $search,
        ]);
    }

    public function create()
    {
        return view('admin.brands.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'logo' => ['nullable', 'string', 'max:255'],
        ]);

        Brand::create([
            'name' => $validated['name'],
            'logo' => $validated['logo'] ?? null,
        ]);

        return redirect()->route('admin.brands.index')->with('status', 'Brand berhasil dibuat.');
    }

    public function edit(Brand $brand)
    {
        return view('admin.brands.edit', [
            'brand' => $brand,
        ]);
    }

    public function update(Request $request, Brand $brand): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'logo' => ['nullable', 'string', 'max:255'],
        ]);

        $brand->update([
            'name' => $validated['name'],
            'logo' => $validated['logo'] ?? null,
        ]);

        return redirect()->route('admin.brands.index')->with('status', 'Brand berhasil diupdate.');
    }

    public function destroy(Brand $brand): RedirectResponse
    {
        $brand->loadCount('products');

        if ($brand->products_count > 0) {
            return back()->with('status', 'Brand tidak bisa dihapus karena masih dipakai product.');
        }

        $brand->delete();

        return redirect()->route('admin.brands.index')->with('status', 'Brand berhasil dihapus.');
    }
}
