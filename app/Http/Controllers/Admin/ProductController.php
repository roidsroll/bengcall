<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('q', ''));

        $products = Product::query()
            ->with(['category', 'brand'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($inner) use ($search) {
                    $inner
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('code_parts', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.products.index', [
            'products' => $products,
            'search' => $search,
        ]);
    }

    public function create()
    {
        $categories = Category::query()->orderBy('name')->get(['id', 'name', 'code']);

        return view('admin.products.create', [
            'categories' => $categories,
            'categoryCodeParts' => $this->buildCategoryCodePartsMap($categories),
            'brands' => Brand::query()->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'brand_id' => ['required', 'integer', 'exists:brands,id'],
            'name' => ['required', 'string', 'max:255'],
            'products_images' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'stock' => ['nullable', 'integer', 'min:0'],
            'unit' => ['required', 'string', 'max:50'],
            'purchase_price' => ['nullable', 'numeric', 'min:0'],
            'sell_price' => ['nullable', 'numeric', 'min:0'],
            'min_stock' => ['nullable', 'integer', 'min:0'],
            'description' => ['nullable', 'string', 'max:5000'],
        ]);

        $imagePath = $request->file('products_images')
            ? $this->storeProductImage($request->file('products_images'), $validated['name'])
            : null;

        Product::create([
            'category_id' => (int) $validated['category_id'],
            'brand_id' => (int) $validated['brand_id'],
            'name' => $validated['name'],
            'products_images' => $imagePath,
            'stock' => $validated['stock'] ?? 0,
            'code_parts' => $this->generateCodeParts((int) $validated['category_id']),
            'part_number' => null,
            'unit' => $validated['unit'],
            'purchase_price' => $validated['purchase_price'] ?? 0,
            'sell_price' => $validated['sell_price'] ?? 0,
            'min_stock' => $validated['min_stock'] ?? 0,
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('admin.products.index')->with('status', 'Product berhasil dibuat.');
    }

    public function edit(Product $product)
    {
        $categories = Category::query()->orderBy('name')->get(['id', 'name', 'code']);

        return view('admin.products.edit', [
            'product' => $product->load(['category', 'brand']),
            'categories' => $categories,
            'categoryCodeParts' => $this->buildCategoryCodePartsMap($categories, $product),
            'brands' => Brand::query()->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'brand_id' => ['required', 'integer', 'exists:brands,id'],
            'name' => ['required', 'string', 'max:255'],
            'products_images' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'unit' => ['required', 'string', 'max:50'],
            'purchase_price' => ['nullable', 'numeric', 'min:0'],
            'sell_price' => ['nullable', 'numeric', 'min:0'],
            'min_stock' => ['nullable', 'integer', 'min:0'],
            'description' => ['nullable', 'string', 'max:5000'],
        ]);

        $imagePath = $product->products_images;

        if ($request->hasFile('products_images')) {
            $imagePath = $this->storeProductImage($request->file('products_images'), $validated['name']);
            $this->deleteProductImage($product->products_images);
        }

        $product->update([
            'category_id' => (int) $validated['category_id'],
            'brand_id' => (int) $validated['brand_id'],
            'name' => $validated['name'],
            'products_images' => $imagePath,
            'code_parts' => $this->generateCodeParts((int) $validated['category_id'], $product),
            'part_number' => null,
            'unit' => $validated['unit'],
            'purchase_price' => $validated['purchase_price'] ?? 0,
            'sell_price' => $validated['sell_price'] ?? 0,
            'min_stock' => $validated['min_stock'] ?? 0,
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('admin.products.index')->with('status', 'Product berhasil diupdate.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $this->deleteProductImage($product->products_images);
        $product->delete();

        return redirect()->route('admin.products.index')->with('status', 'Product berhasil dihapus.');
    }

    private function storeProductImage(UploadedFile $image, string $productName): string
    {
        $directory = public_path('images');

        if (! File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        if (! $this->canConvertToWebp()) {
            return $this->storeOriginalImage($image, $productName, $directory);
        }

        $source = $this->createImageResource($image);

        if (! $source) {
            return $this->storeOriginalImage($image, $productName, $directory);
        }

        $width = imagesx($source);
        $height = imagesy($source);
        $canvas = imagecreatetruecolor($width, $height);

        imagealphablending($canvas, false);
        imagesavealpha($canvas, true);

        $transparent = imagecolorallocatealpha($canvas, 0, 0, 0, 127);
        imagefilledrectangle($canvas, 0, 0, $width, $height, $transparent);
        imagecopy($canvas, $source, 0, 0, 0, 0, $width, $height);

        $filename = Str::slug($productName) . '-' . Str::random(10) . '.webp';
        $fullPath = $directory . DIRECTORY_SEPARATOR . $filename;

        $saved = imagewebp($canvas, $fullPath, 85);

        imagedestroy($source);
        imagedestroy($canvas);

        if (! $saved) {
            return $this->storeOriginalImage($image, $productName, $directory);
        }

        return 'images/' . $filename;
    }

    private function canConvertToWebp(): bool
    {
        return function_exists('imagewebp')
            && function_exists('imagecreatetruecolor')
            && function_exists('imagesavealpha');
    }

    private function storeOriginalImage(UploadedFile $image, string $productName, string $directory): string
    {
        $extension = strtolower($image->getClientOriginalExtension());
        $extension = in_array($extension, ['jpg', 'jpeg', 'png', 'webp'], true) ? $extension : 'jpg';

        $filename = Str::slug($productName) . '-' . Str::random(10) . '.' . $extension;
        $image->move($directory, $filename);

        return 'images/' . $filename;
    }

    private function createImageResource(UploadedFile $image)
    {
        return match ($image->getMimeType()) {
            'image/jpeg', 'image/jpg' => imagecreatefromjpeg($image->getRealPath()),
            'image/png' => imagecreatefrompng($image->getRealPath()),
            'image/webp' => imagecreatefromwebp($image->getRealPath()),
            default => null,
        };
    }

    private function deleteProductImage(?string $path): void
    {
        if (! $path) {
            return;
        }

        $fullPath = public_path(str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path));

        if (File::exists($fullPath)) {
            File::delete($fullPath);
        }
    }

    private function buildCategoryCodePartsMap($categories, ?Product $product = null): array
    {
        $map = [];

        foreach ($categories as $category) {
            $map[(string) $category->id] = $this->generateCodeParts((int) $category->id, $product);
        }

        return $map;
    }

    private function generateCodeParts(int $categoryId, ?Product $product = null): string
    {
        $category = Category::query()->findOrFail($categoryId);
        $categoryCode = strtoupper(trim((string) $category->code));

        if ($categoryCode === '') {
            return '000';
        }

        if ($product && (int) $product->category_id === $categoryId && filled($product->code_parts)) {
            return (string) $product->code_parts;
        }

        $latestCodeParts = Product::query()
            ->where('category_id', $categoryId)
            ->when($product, fn ($query) => $query->whereKeyNot($product->id))
            ->whereNotNull('code_parts')
            ->pluck('code_parts');

        $maxSequence = 0;

        foreach ($latestCodeParts as $codeParts) {
            if (preg_match('/^' . preg_quote($categoryCode, '/') . '-(\d+)$/', (string) $codeParts, $matches) === 1) {
                $maxSequence = max($maxSequence, (int) $matches[1]);
            }
        }

        return $categoryCode . '-' . str_pad((string) ($maxSequence + 1), 3, '0', STR_PAD_LEFT);
    }
}
