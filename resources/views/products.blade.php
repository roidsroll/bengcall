@extends('layouts.user', ['title' => 'bengcall - Bengkel Panggilan', 'mainClass' => 'p-0'])

@section('content')
    <div class="w-full bg-slate-50">
        <section class="bg-gradient-to-br from-[#fff3f3] via-white to-[#f8d9d9]">
            <div class="mx-auto max-w-6xl px-6 py-12">
                <div class="mx-auto max-w-3xl text-center">
                    <span class="inline-flex rounded-full bg-[#CE2626]/10 px-4 py-1 text-sm font-semibold text-[#CE2626] ring-1 ring-[#CE2626]/20">
                        Produk Bengcall
                    </span>
                    <h1 class="mt-4 text-4xl font-semibold tracking-tight text-slate-900 sm:text-5xl">
                        Sparepart dan kebutuhan bengkel dari data produk asli
                    </h1>
                    <p class="mt-4 text-base leading-7 text-slate-600">
                        Halaman ini sekarang langsung membaca tabel <span class="font-semibold text-slate-800">products</span>,
                        lengkap dengan kategori dan brand yang terhubung.
                    </p>
                </div>

                <div class="mx-auto mt-8 max-w-2xl">
                    <form action="{{ route('products') }}" method="GET" class="relative flex items-center">
                        <div class="relative w-full">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                                <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>

                            <input
                                type="text"
                                name="search"
                                value="{{ $search }}"
                                placeholder="Cari nama produk, part number, brand, atau kategori..."
                                class="block w-full rounded-full border border-slate-200 bg-white py-3 pl-11 pr-32 text-sm shadow-sm focus:border-[#CE2626] focus:outline-none focus:ring-1 focus:ring-[#CE2626]"
                            >

                            <div class="absolute inset-y-1.5 right-1.5 flex gap-2">
                                @if ($search !== '')
                                    <a
                                        href="{{ route('products') }}"
                                        class="inline-flex items-center rounded-full border border-slate-200 px-4 text-sm font-medium text-slate-600 transition-colors hover:bg-slate-50"
                                    >
                                        Reset
                                    </a>
                                @endif
                                <button type="submit" class="rounded-full bg-[#CE2626] px-6 text-sm font-medium text-white transition-colors hover:bg-[#b81f1f]">
                                    Cari
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>

        <section class="mx-auto max-w-6xl px-6 py-8">
            <div class="mb-6 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-slate-900">Katalog Produk</h2>
                    <p class="text-sm text-slate-600">
                        Menampilkan {{ $products->total() }} produk{{ $search !== '' ? " untuk pencarian \"{$search}\"" : '' }}.
                    </p>
                </div>
            </div>

            @if ($products->count() > 0)
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-3">
                    @foreach ($products as $product)
                        @php
                            $brandName = $product->brand?->name ?? 'Tanpa Brand';
                            $categoryName = $product->category?->name ?? 'Tanpa Kategori';
                            $initials = strtoupper(substr($brandName, 0, 1) . substr($categoryName, 0, 1));
                        @endphp

                        <article class="flex h-full flex-col overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-xl">
                            <div class="relative overflow-hidden">
                                @if ($product->products_images)
                                    <img
                                        src="{{ asset($product->products_images) }}"
                                        alt="{{ $product->name }}"
                                        class="h-56 w-full object-cover"
                                    >
                                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/85 via-slate-900/25 to-transparent"></div>
                                @else
                                    <div class="bg-gradient-to-br from-slate-900 via-slate-800 to-[#CE2626] px-6 py-8 text-white">
                                        <div class="absolute -right-8 -top-8 h-24 w-24 rounded-full bg-white/10"></div>
                                        <div class="absolute -bottom-10 left-6 h-20 w-20 rounded-full bg-white/5"></div>
                                    </div>
                                @endif

                                <div class="absolute inset-x-0 bottom-0 flex items-end justify-between gap-4 px-6 py-6 text-white">
                                    <div class="max-w-[75%]">
                                        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-white/70">{{ $categoryName }}</p>
                                        <h3 class="mt-3 text-2xl font-semibold leading-tight">{{ $product->name }}</h3>
                                    </div>
                                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl border border-white/15 bg-white/10 text-lg font-bold backdrop-blur-sm">
                                        {{ $initials }}
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-1 flex-col p-6">
                                <div class="flex flex-wrap items-center gap-2 text-xs font-medium">
                                    <span class="rounded-full bg-slate-100 px-3 py-1 text-slate-700">{{ $brandName }}</span>
                                    <span class="rounded-full bg-[#CE2626]/10 px-3 py-1 text-[#B91C1C]">Unit: {{ $product->unit }}</span>
                                    @if ($product->part_number)
                                        <span class="rounded-full bg-amber-50 px-3 py-1 text-amber-700">Part #: {{ $product->part_number }}</span>
                                    @endif
                                </div>

                                <p class="mt-4 flex-1 text-sm leading-6 text-slate-600">
                                    {{ $product->description ?: 'Belum ada deskripsi untuk produk ini. Data yang ditampilkan berasal langsung dari master product.' }}
                                </p>

                                <div class="mt-6 grid grid-cols-2 gap-3 rounded-2xl bg-slate-50 p-4 text-sm text-slate-600">
                                    <div>
                                        <p class="text-xs uppercase tracking-wide text-slate-500">Harga jual</p>
                                        <p class="mt-1 text-lg font-semibold text-slate-900">
                                            Rp {{ number_format((float) $product->sell_price, 0, ',', '.') }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-xs uppercase tracking-wide text-slate-500">Min stock</p>
                                        <p class="mt-1 text-lg font-semibold text-slate-900">{{ $product->min_stock }}</p>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $products->links() }}
                </div>
            @else
                <div class="rounded-3xl border border-dashed border-slate-300 bg-white px-6 py-14 text-center shadow-sm">
                    <h3 class="text-xl font-semibold text-slate-900">Produk tidak ditemukan</h3>
                    <p class="mx-auto mt-3 max-w-xl text-sm leading-6 text-slate-600">
                        @if ($search !== '')
                            Tidak ada produk yang cocok dengan kata kunci <span class="font-semibold text-slate-800">{{ $search }}</span>.
                            Coba gunakan nama produk, brand, kategori, atau part number lain.
                        @else
                            Belum ada data pada tabel products yang bisa ditampilkan di halaman ini.
                        @endif
                    </p>
                </div>
            @endif
        </section>
    </div>
@endsection
