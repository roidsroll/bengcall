@extends('layouts.admin', ['title' => 'Detail Order Service - bengcall'])

@section('content')
    <div class="mx-auto max-w-5xl space-y-4">
        @php
            $walkIn = null;
            $displayNotes = $orderService->customer_notes;

            if (is_string($displayNotes) && str_starts_with($displayNotes, '__walk_in__=')) {
                $pos = strpos($displayNotes, "\n");
                $firstLine = $pos === false ? $displayNotes : substr($displayNotes, 0, $pos);
                $json = substr($firstLine, strlen('__walk_in__='));
                $decoded = json_decode($json, true);
                if (is_array($decoded)) {
                    $walkIn = $decoded;
                }

                $rest = $pos === false ? '' : trim(substr($displayNotes, $pos + 1));
                $displayNotes = $rest !== '' ? $rest : null;
            }
        @endphp

        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold tracking-tight text-slate-900">Detail Order Service</h1>
                <p class="mt-1 text-sm text-slate-600">{{ $orderService->order_number }}</p>
            </div>
            <a
                href="{{ route('admin.order-services.index') }}"
                class="rounded-xl bg-white px-4 py-2 text-sm font-semibold text-slate-900 ring-1 ring-slate-200 hover:bg-slate-50"
            >
                Kembali
            </a>
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Customer</p>
                    @if ($walkIn)
                        <p class="mt-1 font-semibold text-slate-900">{{ $walkIn['name'] ?? 'Walk In' }}</p>
                        <p class="text-sm text-slate-600">{{ $walkIn['call_number'] ?? '-' }}</p>
                        @if (! empty($walkIn['address']))
                            <p class="mt-1 text-xs text-slate-600">{{ $walkIn['address'] }}</p>
                        @endif
                    @else
                        <p class="mt-1 font-semibold text-slate-900">{{ $orderService->customer?->name ?? '-' }}</p>
                        <p class="text-sm text-slate-600">{{ $orderService->customer?->email ?? '-' }}</p>
                    @endif
                </div>

                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Teknisi</p>
                    <p class="mt-1 font-semibold text-slate-900">{{ $orderService->technician?->name ?? '-' }}</p>
                    <p class="text-sm text-slate-600">{{ $orderService->technician?->email ?? '-' }}</p>
                </div>

                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Kendaraan</p>
                    <p class="mt-1 font-semibold text-slate-900">{{ $orderService->vehicle_name }}</p>
                    <p class="text-sm text-slate-600">Plat: {{ $orderService->license_plate }}</p>
                </div>

                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Total</p>
                    <p class="mt-1 text-lg font-semibold text-slate-900">{{ number_format((float) $orderService->total_price, 0, ',', '.') }}</p>
                    <p class="text-sm text-slate-600">
                        Status: {{ ucfirst($orderService->status) }} | Payment: {{ ucfirst($orderService->payment_status) }}
                    </p>
                </div>

                @if ($displayNotes)
                    <div class="sm:col-span-2">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Catatan</p>
                        <p class="mt-1 text-sm text-slate-700">{{ $displayNotes }}</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
            <div class="overflow-x-auto">
                <table class="min-w-full table-fixed divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr class="text-left text-xs font-semibold uppercase tracking-wider text-slate-600">
                            <th class="px-5 py-3">Item</th>
                            <th class="w-[120px] px-5 py-3">Qty</th>
                            <th class="w-[160px] px-5 py-3">Harga</th>
                            <th class="w-[160px] px-5 py-3">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @forelse ($orderService->details as $detail)
                            <tr class="text-sm text-slate-700">
                                <td class="px-5 py-3 align-top">
                                    <p class="font-semibold text-slate-900">{{ $detail->item_name }}</p>
                                </td>
                                <td class="px-5 py-3 align-top">{{ (int) $detail->quantity }}</td>
                                <td class="px-5 py-3 align-top">{{ number_format((float) $detail->price, 0, ',', '.') }}</td>
                                <td class="px-5 py-3 align-top">{{ number_format((float) $detail->subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-10 text-center text-sm text-slate-600">
                                    Item order kosong.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
