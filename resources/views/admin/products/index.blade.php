@extends('layouts.admin', ['title' => 'Master Products - bengcall'])

@section('content')
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight text-slate-900">Master Products</h1>
            <p class="mt-1 text-sm text-slate-600">CRUD untuk data product.</p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <form method="GET" action="{{ route('admin.products.index') }}" class="flex items-center gap-2">
                <input
                    type="text"
                    name="q"
                    value="{{ $search ?? '' }}"
                    class="w-64 rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm outline-none focus:border-[#CE2626] focus:ring-4 focus:ring-[#CE2626]/20"
                    placeholder="Cari nama / code parts..."
                />
                <button
                    type="submit"
                    class="rounded-xl bg-white px-4 py-2 text-sm font-semibold text-slate-900 ring-1 ring-slate-200 hover:bg-slate-50"
                >
                    Cari
                </button>
            </form>

            <a
                href="{{ route('admin.products.create') }}"
                class="inline-flex items-center rounded-xl bg-[#CE2626] px-4 py-2 text-sm font-semibold text-white hover:bg-[#b81f1f] focus:outline-none focus:ring-4 focus:ring-[#CE2626]/30"
            >
                + Tambah Product
            </a>
        </div>
    </div>

    <div class="mt-6 overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
        <div class="overflow-x-auto">
            <table class="min-w-full table-fixed divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr class="text-left text-xs font-semibold uppercase tracking-wider text-slate-600">
                        <th class="w-[110px] px-5 py-3">Foto</th>
                        <th class="w-[280px] px-5 py-3">Nama</th>
                        <th class="w-[100px] px-5 py-3">Category</th>
                        <th class="w-[220px] px-5 py-3">Brand</th>
                        <th class="w-[120px] px-5 py-3">Unit</th>
                        <th class="w-[160px] px-5 py-3">Harga Beli</th>
                        <th class="w-[160px] px-5 py-3">Harga Jual</th>
                        <th class="w-[120px] px-5 py-3">Stock</th>
                        <th class="w-[120px] px-5 py-3">Min Stock</th>
                        <th class="w-[220px] px-5 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @forelse ($products as $product)
                        <tr class="text-sm text-slate-700">
                            <td class="px-5 py-3 align-top">
                                @if ($product->products_images)
                                    <img
                                        src="{{ asset($product->products_images) }}"
                                        alt="{{ $product->name }}"
                                        class="h-16 w-16 rounded-xl object-cover ring-1 ring-slate-200"
                                    >
                                @else
                                    <div class="flex h-16 w-16 items-center justify-center rounded-xl bg-slate-100 text-xs font-semibold text-slate-500 ring-1 ring-slate-200">
                                        No Photo
                                    </div>
                                @endif
                            </td>
                            <td class="px-5 py-3 align-top">
                                <p class="font-semibold leading-5 text-slate-900">{{ $product->name }}</p>
                                <p class="mt-1 text-[11px] font-medium text-slate-500 uppercase tracking-wider">
                                    Code Parts: {{ $product->code_parts ?: '-' }}
                                </p>
                            </td>
                            <td class="px-5 py-3 align-top">{{ $product->category?->name ?? '-' }}</td>
                            <td class="px-5 py-3 align-top">{{ $product->brand?->name ?? '-' }}</td>
                            <td class="px-5 py-3 align-top">{{ $product->unit }}</td>
                            <td class="px-5 py-3 align-top">{{ number_format((float) $product->purchase_price, 0, ',', '.') }}</td>
                            <td class="px-5 py-3 align-top">{{ number_format((float) $product->sell_price, 0, ',', '.') }}</td>
                            <td class="px-5 py-3 align-top">{{ $product->stock }}</td>
                            <td class="px-5 py-3 align-top">{{ $product->min_stock }}</td>
                            <td class="px-5 py-3 text-right align-top">
                                <div class="flex items-center justify-end gap-2 whitespace-nowrap">
                                    <a
                                        href="{{ route('admin.products.edit', $product) }}"
                                        class="rounded-lg bg-white px-3 py-1.5 text-xs font-semibold text-slate-900 ring-1 ring-slate-200 hover:bg-slate-50"
                                    >
                                        Edit
                                    </a>
                                    <form
                                        method="POST"
                                        action="{{ route('admin.products.destroy', $product) }}"
                                        onsubmit="return confirm('Yakin hapus product ini?')"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            type="submit"
                                            class="rounded-lg bg-[#CE2626] px-3 py-1.5 text-xs font-semibold text-white hover:bg-[#b81f1f]"
                                        >
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-5 py-10 text-center text-sm text-slate-600">
                                Tidak ada data product.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-slate-200 bg-white px-5 py-4">
            {{ $products->links() }}
        </div>
    </div>
@endsection
