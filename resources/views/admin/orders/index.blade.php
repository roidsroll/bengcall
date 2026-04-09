@extends('layouts.admin', ['title' => 'Orders - bengcall'])

@section('content')
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight text-slate-900">Orders</h1>
            <p class="mt-1 text-sm text-slate-600">Kelola pesanan sparepart.</p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <form method="GET" action="{{ route('admin.orders.index') }}" class="flex items-center gap-2">
                <input
                    type="text"
                    name="q"
                    value="{{ $search ?? '' }}"
                    class="w-64 rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm outline-none focus:border-[#CE2626] focus:ring-4 focus:ring-[#CE2626]/20"
                    placeholder="Cari no order / plat / kendaraan…"
                />
                <button
                    type="submit"
                    class="rounded-xl bg-white px-4 py-2 text-sm font-semibold text-slate-900 ring-1 ring-slate-200 hover:bg-slate-50"
                >
                    Cari
                </button>
            </form>

            <a
                href="{{ route('admin.orders.create') }}"
                class="inline-flex items-center rounded-xl bg-[#CE2626] px-4 py-2 text-sm font-semibold text-white hover:bg-[#b81f1f] focus:outline-none focus:ring-4 focus:ring-[#CE2626]/30"
            >
                + Buat Order
            </a>
        </div>
    </div>

    <div class="mt-6 overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
        <div class="overflow-x-auto">
            <table class="min-w-full table-fixed divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr class="text-left text-xs font-semibold uppercase tracking-wider text-slate-600">
                        <th class="w-[220px] px-5 py-3">No Order</th>
                        <th class="w-[240px] px-5 py-3">Customer</th>
                        <th class="w-[260px] px-5 py-3">Kendaraan</th>
                        <th class="w-[160px] px-5 py-3">Total</th>
                        <th class="w-[160px] px-5 py-3">Status</th>
                        <th class="w-[180px] px-5 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @forelse ($orders as $order)
                        @php
                            $walkIn = null;

                            if (is_string($order->customer_notes) && str_starts_with($order->customer_notes, '__walk_in__=')) {
                                $pos = strpos($order->customer_notes, "\n");
                                $firstLine = $pos === false ? $order->customer_notes : substr($order->customer_notes, 0, $pos);
                                $json = substr($firstLine, strlen('__walk_in__='));
                                $decoded = json_decode($json, true);
                                if (is_array($decoded)) {
                                    $walkIn = $decoded;
                                }
                            }
                        @endphp
                        <tr class="text-sm text-slate-700">
                            <td class="px-5 py-3 align-top">
                                <p class="font-semibold leading-5 text-slate-900">{{ $order->order_number }}</p>
                                <p class="mt-1 text-xs text-slate-600">
                                    {{ $order->created_at?->format('d M Y H:i') ?? '-' }}
                                </p>
                            </td>
                            <td class="px-5 py-3 align-top">
                                @if ($walkIn)
                                    <p class="font-semibold text-slate-900">{{ $walkIn['name'] ?? 'Walk In' }}</p>
                                    <p class="mt-1 text-xs text-slate-600">{{ $walkIn['call_number'] ?? '-' }}</p>
                                @else
                                    <p class="font-semibold text-slate-900">{{ $order->customer?->name ?? '-' }}</p>
                                    <p class="mt-1 text-xs text-slate-600">{{ $order->customer?->email ?? '-' }}</p>
                                @endif
                            </td>
                            <td class="px-5 py-3 align-top">
                                <p class="font-semibold text-slate-900">{{ $order->vehicle_name }}</p>
                                <p class="mt-1 text-xs text-slate-600">Plat: {{ $order->license_plate }}</p>
                            </td>
                            <td class="px-5 py-3 align-top">
                                {{ number_format((float) $order->total_price, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-3 align-top">
                                <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700 ring-1 ring-slate-200">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-right align-top">
                                <div class="flex items-center justify-end gap-2">
                                    @if ($order->status === 'pending' && $order->payment_status === 'unpaid')
                                        <form method="POST" action="{{ route('admin.orders.cancel', $order) }}">
                                            @csrf
                                            <button
                                                type="submit"
                                                class="rounded-lg bg-red-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-red-700"
                                            >
                                                Cancel
                                            </button>
                                        </form>

                                        <form method="POST" action="{{ route('admin.orders.confirm', $order) }}">
                                            @csrf
                                            <button
                                                type="submit"
                                                class="rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-emerald-700"
                                            >
                                                Konfirmasi
                                            </button>
                                        </form>
                                    @endif

                                    <a
                                        href="{{ route('admin.orders.show', $order) }}"
                                        class="rounded-lg bg-white px-3 py-1.5 text-xs font-semibold text-slate-900 ring-1 ring-slate-200 hover:bg-slate-50"
                                    >
                                        Detail
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-10 text-center text-sm text-slate-600">
                                Tidak ada data order.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-slate-200 bg-white px-5 py-4">
            {{ $orders->links() }}
        </div>
    </div>
@endsection
