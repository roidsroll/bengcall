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
                        Cari Sparepart kendaraan kamu di sini
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
                                placeholder="Cari nama produk, code parts, brand, atau kategori..."
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
            @if ($errors->any())
                <div class="mb-6 rounded-2xl bg-red-50 px-4 py-3 text-sm text-red-800 ring-1 ring-red-200">
                    <ul class="list-disc space-y-1 pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif



            @if ($products->count() > 0)
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-3">
                    @foreach ($products as $product)
                        @php
                            $brandName = $product->brand?->name ?? 'Tanpa Brand';
                            $categoryName = $product->category?->name ?? 'Tanpa Kategori';
                            $initials = strtoupper(substr($brandName, 0, 1) . substr($categoryName, 0, 1));
                            $productStock = (int) $product->stock;
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
                                    @if ($product->code_parts)
                                        <span class="rounded-full bg-amber-50 px-3 py-1 text-amber-700">Code Parts: {{ $product->code_parts }}</span>
                                    @endif
                                </div>

                                <p class="mt-4 flex-1 text-sm leading-6 text-slate-600">
                                    {{ $product->description ?: 'Belum ada deskripsi untuk produk ini. Data yang ditampilkan berasal langsung dari master product.' }}
                                </p>

                                <div class="mt-6 grid grid-cols-3 gap-3 rounded-2xl bg-slate-50 p-4 text-sm text-slate-600">
                                    <div>
                                        <p class="text-xs uppercase tracking-wide text-slate-500">Harga jual</p>
                                        <p class="mt-1 text-lg font-semibold text-slate-900">
                                            Rp {{ number_format((float) $product->sell_price, 0, ',', '.') }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-xs uppercase tracking-wide text-slate-500">Stock</p>
                                        <p class="mt-1 text-lg font-semibold text-slate-900">{{ $productStock }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs uppercase tracking-wide text-slate-500">Min stock</p>
                                        <p class="mt-1 text-lg font-semibold text-slate-900">{{ $product->min_stock }}</p>
                                    </div>
                                </div>

                                <div class="mt-6">
                                    @if ($productStock > 0)
                                        <button
                                            type="button"
                                            class="open-order-modal inline-flex w-full items-center justify-center rounded-2xl bg-[#CE2626] px-4 py-3 text-sm font-semibold text-white transition-colors hover:bg-[#b81f1f]"
                                            data-product-id="{{ $product->id }}"
                                            data-product-name="{{ $product->name }}"
                                            data-product-price="{{ number_format((float) $product->sell_price, 0, ',', '.') }}"
                                            data-product-stock="{{ $productStock }}"
                                        >
                                            Pesan Sekarang
                                        </button>
                                    @else
                                        <button
                                            type="button"
                                            disabled
                                            class="inline-flex w-full cursor-not-allowed items-center justify-center rounded-2xl bg-slate-200 px-4 py-3 text-sm font-semibold text-slate-500"
                                        >
                                            Stock Habis
                                        </button>
                                    @endif
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
                            Coba gunakan nama produk, brand, kategori, atau code parts lain.
                        @else
                            Belum ada data pada tabel products yang bisa ditampilkan di halaman ini.
                        @endif
                    </p>
                </div>
            @endif
        </section>

        <div
            id="login-required-modal"
            class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/60 px-4"
            aria-hidden="true"
        >
            <div class="w-full max-w-md rounded-3xl bg-white p-6 shadow-2xl">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-lg font-semibold text-slate-900">Login Dibutuhkan</p>
                        <p class="mt-2 text-sm leading-6 text-slate-600">Silahkan login terlebih dahulu untuk memesan produk.</p>
                    </div>
                    <button type="button" class="close-modal rounded-xl px-3 py-2 text-slate-500 hover:bg-slate-100">Tutup</button>
                </div>

                <div class="mt-6 flex gap-3">
                    <button type="button" class="close-modal flex-1 rounded-2xl bg-white px-4 py-3 text-sm font-semibold text-slate-900 ring-1 ring-slate-200 hover:bg-slate-50">Nanti Saja</button>
                    <a href="{{ route('login') }}" class="flex-1 rounded-2xl bg-[#CE2626] px-4 py-3 text-center text-sm font-semibold text-white hover:bg-[#b81f1f]">Login</a>
                </div>
            </div>
        </div>

        <div
            id="order-confirm-modal"
            class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/60 px-4 py-6"
            aria-hidden="true"
        >
            <div class="w-full max-w-lg rounded-3xl bg-white p-6 shadow-2xl">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-lg font-semibold text-slate-900">Konfirmasi Pesanan</p>
                        <p class="mt-2 text-sm leading-6 text-slate-600">Isi jumlah dan data kendaraan, lalu pesanan akan dibuat otomatis.</p>
                    </div>
                    <button type="button" class="close-modal rounded-xl px-3 py-2 text-slate-500 hover:bg-slate-100">Tutup</button>
                </div>

                <form id="order-form" method="POST" action="">
                    @csrf

                    <div class="mt-6 rounded-2xl bg-slate-50 p-4">
                        <p id="modal-product-name" class="text-base font-semibold text-slate-900"></p>
                        <div class="mt-2 flex flex-wrap gap-3 text-sm text-slate-600">
                            <span>Harga: Rp <span id="modal-product-price"></span></span>
                            <span>Stock tersedia: <span id="modal-product-stock"></span></span>
                        </div>
                    </div>

                    <div class="mt-6 grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="text-sm font-medium text-slate-700" for="quantity">Jumlah</label>
                            <input
                                id="quantity"
                                name="quantity"
                                type="number"
                                min="1"
                                value="1"
                                required
                                class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-slate-900 shadow-sm outline-none focus:border-[#CE2626] focus:ring-4 focus:ring-[#CE2626]/20"
                            />
                        </div>
                        <div class="sm:col-span-2">
                            <label class="text-sm font-medium text-slate-700" for="customer_notes">Catatan (opsional)</label>
                            <textarea
                                id="customer_notes"
                                name="customer_notes"
                                rows="3"
                                class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-slate-900 shadow-sm outline-none focus:border-[#CE2626] focus:ring-4 focus:ring-[#CE2626]/20"
                            ></textarea>
                        </div>
                    </div>

                    <p id="order-modal-error" class="mt-4 hidden rounded-2xl bg-red-50 px-4 py-3 text-sm text-red-700 ring-1 ring-red-200"></p>

                    <div class="mt-6 flex gap-3">
                        <button type="button" class="close-modal flex-1 rounded-2xl bg-white px-4 py-3 text-sm font-semibold text-slate-900 ring-1 ring-slate-200 hover:bg-slate-50">Batal</button>
                        <button type="submit" class="flex-1 rounded-2xl bg-[#CE2626] px-4 py-3 text-sm font-semibold text-white hover:bg-[#b81f1f]">Konfirmasi Pesan</button>
                    </div>
                </form>
            </div>
        </div>

        <div
            id="order-success-modal"
            class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/60 px-4"
            aria-hidden="true"
        >
            <div class="w-full max-w-md rounded-3xl bg-white p-6 shadow-2xl">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-lg font-semibold text-slate-900">Pesanan Berhasil</p>
                        <p class="mt-2 text-sm leading-6 text-slate-600">{{ session('order_success_message') }}</p>
                    </div>
                    <button type="button" class="close-modal rounded-xl px-3 py-2 text-slate-500 hover:bg-slate-100">Tutup</button>
                </div>

                <div class="mt-6 flex gap-3">
                    <a
                        href="{{ route('user.home') }}"
                        class="flex-1 rounded-2xl bg-white px-4 py-3 text-center text-sm font-semibold text-slate-900 ring-1 ring-slate-200 hover:bg-slate-50"
                    >
                        Lihat Pesanan
                    </a>
                    <button
                        type="button"
                        class="close-modal flex-1 rounded-2xl bg-[#CE2626] px-4 py-3 text-sm font-semibold text-white hover:bg-[#b81f1f]"
                    >
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const isAuthenticated = @json(auth()->check());
            const orderSuccessMessage = @json(session('order_success_message'));
            const loginRequiredModal = document.getElementById('login-required-modal');
            const orderConfirmModal = document.getElementById('order-confirm-modal');
            const orderSuccessModal = document.getElementById('order-success-modal');
            const orderForm = document.getElementById('order-form');
            const productName = document.getElementById('modal-product-name');
            const productPrice = document.getElementById('modal-product-price');
            const productStock = document.getElementById('modal-product-stock');
            const quantityInput = document.getElementById('quantity');
            const customerNotesInput = document.getElementById('customer_notes');
            const errorBox = document.getElementById('order-modal-error');
            const openerButtons = document.querySelectorAll('.open-order-modal');
            const closeButtons = document.querySelectorAll('.close-modal');

            const openModal = function (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            };

            const closeModal = function (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            };

            openerButtons.forEach(function (button) {
                button.addEventListener('click', function () {
                    if (!isAuthenticated) {
                        openModal(loginRequiredModal);
                        return;
                    }

                    const stock = Number(this.dataset.productStock || 0);
                    const productId = this.dataset.productId;

                    productName.textContent = this.dataset.productName || '';
                    productPrice.textContent = this.dataset.productPrice || '0';
                    productStock.textContent = String(stock);
                    quantityInput.value = '1';
                    quantityInput.max = String(stock);
                    customerNotesInput.value = '';
                    errorBox.classList.add('hidden');
                    errorBox.textContent = '';
                    orderForm.action = "{{ url('/products') }}/" + productId + "/order";

                    openModal(orderConfirmModal);
                });
            });

            closeButtons.forEach(function (button) {
                button.addEventListener('click', function () {
                    closeModal(loginRequiredModal);
                    closeModal(orderConfirmModal);
                    closeModal(orderSuccessModal);
                });
            });

            [loginRequiredModal, orderConfirmModal].forEach(function (modal) {
                if (!modal) {
                    return;
                }

                modal.addEventListener('click', function (event) {
                    if (event.target === modal) {
                        closeModal(modal);
                    }
                });
            });

            if (orderSuccessModal && orderSuccessMessage) {
                openModal(orderSuccessModal);

                orderSuccessModal.addEventListener('click', function (event) {
                    if (event.target === orderSuccessModal) {
                        closeModal(orderSuccessModal);
                    }
                });
            }

            orderForm.addEventListener('submit', function (event) {
                const quantity = Number(quantityInput.value || 0);
                const stock = Number(productStock.textContent || 0);

                if (quantity < 1) {
                    event.preventDefault();
                    errorBox.textContent = 'Jumlah minimal 1.';
                    errorBox.classList.remove('hidden');
                    return;
                }

                if (quantity > stock) {
                    event.preventDefault();
                    errorBox.textContent = 'Jumlah melebihi stock yang tersedia.';
                    errorBox.classList.remove('hidden');
                    return;
                }
            });
        });
    </script>
@endpush
