@extends('layouts.user', ['title' => 'User Page - bengcall', 'mainClass' => 'mx-auto w-full max-w-6xl px-6 py-10'])

@section('content')
    <div class="space-y-8">
        <div class="rounded-2xl bg-white p-8 shadow-sm ring-1 ring-slate-200">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-semibold tracking-tight text-slate-900">User Page</h1>
                    <p class="mt-1 text-sm text-slate-600">Halo, {{ auth()->user()->name }}.</p>
                </div>
                <span class="inline-flex items-center rounded-full bg-[#CE2626] px-3 py-1 text-xs font-semibold text-white">
                    USER
                </span>
            </div>

            <div class="mt-6 grid gap-4 sm:grid-cols-2">
                <div class="rounded-2xl bg-slate-50 p-5 ring-1 ring-slate-200">
                    <p class="text-sm font-medium text-slate-700">Email</p>
                    <p class="mt-2 font-semibold text-slate-900">{{ auth()->user()->email }}</p>
                </div>
                <div class="rounded-2xl bg-slate-50 p-5 ring-1 ring-slate-200">
                    <p class="text-sm font-medium text-slate-700">Gender</p>
                    <p class="mt-2 font-semibold text-slate-900">{{ auth()->user()->gender }}</p>
                </div>
            </div>
        </div>

        <div class="rounded-2xl bg-white p-8 shadow-sm ring-1 ring-slate-200">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-2xl font-semibold tracking-tight text-slate-900">Barang Yang di Pesan</h2>
                    <p class="mt-1 text-sm text-slate-600">Daftar barang yang sudah Anda pesan dari halaman produk.</p>
                </div>
                <a
                    href="{{ route('products') }}"
                    class="inline-flex items-center rounded-xl bg-[#CE2626] px-4 py-2 text-sm font-semibold text-white hover:bg-[#b81f1f]"
                >
                    Pesan Lagi
                </a>
            </div>

            @if ($orders->isNotEmpty())
                <div class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                    @foreach ($orders as $order)
                        @php
                            $partDetails = $order->details->where('type', 'part');
                            $firstDetail = $partDetails->first();
                            $firstProduct = $firstDetail?->product;
                        @endphp

                        <article class="overflow-hidden rounded-3xl border border-slate-200 bg-slate-50 shadow-sm">
                            <div class="relative">
                                @if ($firstProduct?->products_images)
                                    <img
                                        src="{{ asset($firstProduct->products_images) }}"
                                        alt="{{ $firstDetail->item_name }}"
                                        class="h-44 w-full object-cover"
                                    >
                                @else
                                    <div class="flex h-44 items-center justify-center bg-gradient-to-br from-slate-900 via-slate-800 to-[#CE2626] text-lg font-semibold text-white">
                                        {{ strtoupper(substr((string) $firstDetail?->item_name, 0, 2)) ?: 'PR' }}
                                    </div>
                                @endif
                            </div>

                            <div class="p-5">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">{{ $order->order_number }}</p>
                                        <h3 class="mt-2 text-lg font-semibold text-slate-900">
                                            {{ $firstDetail?->item_name ?? 'Pesanan Produk' }}
                                        </h3>
                                    </div>
                                    <span class="rounded-full bg-[#CE2626]/10 px-3 py-1 text-xs font-semibold uppercase text-[#B91C1C]">
                                        {{ $order->status }}
                                    </span>
                                </div>

                                <div class="mt-4 flex flex-wrap gap-2 text-xs font-medium">
                                    @if ($firstProduct?->code_parts)
                                        <span class="rounded-full bg-amber-50 px-3 py-1 text-amber-700">
                                            {{ $firstProduct->code_parts }}
                                        </span>
                                    @endif
                                    <span class="rounded-full bg-slate-200 px-3 py-1 text-slate-700">
                                        {{ ucfirst($order->payment_status) }}
                                    </span>
                                    <span class="rounded-full bg-white px-3 py-1 text-slate-700 ring-1 ring-slate-200">
                                        {{ $partDetails->sum('quantity') }} item
                                    </span>
                                </div>

                                <div class="mt-4 space-y-2 text-sm text-slate-600">
                                    @foreach ($partDetails->take(3) as $detail)
                                        <div class="flex items-center justify-between gap-3 rounded-2xl bg-white px-3 py-2 ring-1 ring-slate-200">
                                            <span class="truncate">{{ $detail->item_name }}</span>
                                            <span class="whitespace-nowrap font-semibold text-slate-900">x{{ $detail->quantity }}</span>
                                        </div>
                                    @endforeach
                                </div>

                                @if ($partDetails->count() > 3)
                                    <p class="mt-3 text-xs text-slate-500">+{{ $partDetails->count() - 3 }} barang lainnya</p>
                                @endif

                                <div class="mt-5 grid grid-cols-2 gap-3 rounded-2xl bg-white p-4 ring-1 ring-slate-200">
                                    <div>
                                        <p class="text-xs uppercase tracking-wide text-slate-500">Tanggal</p>
                                        <p class="mt-1 text-sm font-semibold text-slate-900">{{ $order->created_at?->format('d M Y H:i') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs uppercase tracking-wide text-slate-500">Total</p>
                                        <p class="mt-1 text-sm font-semibold text-slate-900">
                                            Rp {{ number_format((float) $order->total_price, 0, ',', '.') }}
                                        </p>
                                    </div>
                                </div>

                                @if (filled($order->customer_notes))
                                    <div class="mt-4 rounded-2xl bg-white px-4 py-3 text-sm text-slate-600 ring-1 ring-slate-200">
                                        {{ $order->customer_notes }}
                                    </div>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="mt-6 rounded-3xl border border-dashed border-slate-300 bg-slate-50 px-6 py-12 text-center">
                    <h3 class="text-lg font-semibold text-slate-900">Belum ada barang yang dipesan</h3>
                    <p class="mt-2 text-sm text-slate-600">Saat Anda memesan produk, daftar pesanan akan tampil di sini.</p>
                </div>
            @endif
        </div>
    </div>
@endsection
