@extends('layouts.admin', ['title' => 'Edit Product - bengcall'])

@section('content')
    <div class="mx-auto max-w-3xl">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold tracking-tight text-slate-900">Edit Product</h1>
                <p class="mt-1 text-sm text-slate-600">Update data product.</p>
            </div>
            <a
                href="{{ route('admin.products.index') }}"
                class="rounded-xl bg-white px-4 py-2 text-sm font-semibold text-slate-900 ring-1 ring-slate-200 hover:bg-slate-50"
            >
                Kembali
            </a>
        </div>

        @if ($errors->any())
            <div class="mt-4 rounded-xl bg-red-50 px-4 py-3 text-sm text-red-800 ring-1 ring-red-200">
                <ul class="list-disc space-y-1 pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.products.update', $product) }}" class="mt-6 space-y-4">
            @csrf
            @method('PUT')

            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="text-sm font-medium text-slate-700" for="category_id">Category</label>
                        <select
                            id="category_id"
                            name="category_id"
                            required
                            class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-slate-900 shadow-sm outline-none focus:border-[#CE2626] focus:ring-4 focus:ring-[#CE2626]/20"
                        >
                            <option value="">- Pilih Category -</option>
                            @foreach ($categories as $category)
                                <option
                                    value="{{ $category->id }}"
                                    @selected((string) old('category_id', (string) $product->category_id) === (string) $category->id)
                                >
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-slate-700" for="brand_id">Brand</label>
                        <select
                            id="brand_id"
                            name="brand_id"
                            required
                            class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-slate-900 shadow-sm outline-none focus:border-[#CE2626] focus:ring-4 focus:ring-[#CE2626]/20"
                        >
                            <option value="">- Pilih Brand -</option>
                            @foreach ($brands as $brand)
                                <option
                                    value="{{ $brand->id }}"
                                    @selected((string) old('brand_id', (string) $product->brand_id) === (string) $brand->id)
                                >
                                    {{ $brand->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="sm:col-span-2">
                        <label class="text-sm font-medium text-slate-700" for="name">Nama</label>
                        <input
                            id="name"
                            name="name"
                            type="text"
                            value="{{ old('name', $product->name) }}"
                            required
                            class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-slate-900 shadow-sm outline-none focus:border-[#CE2626] focus:ring-4 focus:ring-[#CE2626]/20"
                        />
                    </div>

                    <div>
                        <label class="text-sm font-medium text-slate-700" for="part_number">Part Number (opsional)</label>
                        <input
                            id="part_number"
                            name="part_number"
                            type="text"
                            value="{{ old('part_number', $product->part_number) }}"
                            class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-slate-900 shadow-sm outline-none focus:border-[#CE2626] focus:ring-4 focus:ring-[#CE2626]/20"
                        />
                    </div>

                    <div>
                        <label class="text-sm font-medium text-slate-700" for="unit">Unit</label>
                        <input
                            id="unit"
                            name="unit"
                            type="text"
                            value="{{ old('unit', $product->unit) }}"
                            required
                            class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-slate-900 shadow-sm outline-none focus:border-[#CE2626] focus:ring-4 focus:ring-[#CE2626]/20"
                        />
                    </div>

                    <div>
                        <label class="text-sm font-medium text-slate-700" for="purchase_price">Harga Beli</label>
                        <input
                            id="purchase_price"
                            name="purchase_price"
                            type="number"
                            min="0"
                            step="0.01"
                            value="{{ old('purchase_price', $product->purchase_price) }}"
                            class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-slate-900 shadow-sm outline-none focus:border-[#CE2626] focus:ring-4 focus:ring-[#CE2626]/20"
                        />
                    </div>

                    <div>
                        <label class="text-sm font-medium text-slate-700" for="sell_price">Harga Jual</label>
                        <input
                            id="sell_price"
                            name="sell_price"
                            type="number"
                            min="0"
                            step="0.01"
                            value="{{ old('sell_price', $product->sell_price) }}"
                            class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-slate-900 shadow-sm outline-none focus:border-[#CE2626] focus:ring-4 focus:ring-[#CE2626]/20"
                        />
                    </div>

                    <div>
                        <label class="text-sm font-medium text-slate-700" for="min_stock">Min Stock</label>
                        <input
                            id="min_stock"
                            name="min_stock"
                            type="number"
                            min="0"
                            value="{{ old('min_stock', $product->min_stock) }}"
                            class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-slate-900 shadow-sm outline-none focus:border-[#CE2626] focus:ring-4 focus:ring-[#CE2626]/20"
                        />
                    </div>

                    <div class="sm:col-span-2">
                        <label class="text-sm font-medium text-slate-700" for="description">Deskripsi (opsional)</label>
                        <textarea
                            id="description"
                            name="description"
                            rows="4"
                            class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-slate-900 shadow-sm outline-none focus:border-[#CE2626] focus:ring-4 focus:ring-[#CE2626]/20"
                        >{{ old('description', $product->description) }}</textarea>
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end gap-3">
                    <a
                        href="{{ route('admin.products.index') }}"
                        class="rounded-xl bg-white px-4 py-2 text-sm font-semibold text-slate-900 ring-1 ring-slate-200 hover:bg-slate-50"
                    >
                        Batal
                    </a>
                    <button
                        type="submit"
                        class="rounded-xl bg-[#CE2626] px-4 py-2 text-sm font-semibold text-white hover:bg-[#b81f1f] focus:outline-none focus:ring-4 focus:ring-[#CE2626]/30"
                    >
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection

