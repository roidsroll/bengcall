@extends('layouts.admin', ['title' => 'Admin Dashboard - bengcall'])

@section('content')
    <div class="rounded-2xl bg-white p-8 shadow-sm ring-1 ring-slate-200">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-semibold tracking-tight text-slate-900">Admin Dashboard</h1>
                <p class="mt-1 text-sm text-slate-600">Selamat datang, {{ auth()->user()->name }}.</p>
            </div>
            <span class="inline-flex items-center rounded-full bg-[#CE2626] px-3 py-1 text-xs font-semibold text-white">
                {{ strtoupper(auth()->user()?->role?->name ?? 'ADMIN') }}
            </span>
        </div>

        <div class="mt-8 grid gap-4 sm:grid-cols-3">
            <div class="rounded-2xl bg-slate-50 p-5 ring-1 ring-slate-200">
                <p class="text-sm font-medium text-slate-700">User</p>
                <p class="mt-2 text-3xl font-semibold text-slate-900">{{ $usersCount ?? '-' }}</p>
            </div>
            <div class="rounded-2xl bg-slate-50 p-5 ring-1 ring-slate-200">
                <p class="text-sm font-medium text-slate-700">Role</p>
                <p class="mt-2 text-3xl font-semibold text-slate-900">{{ $rolesCount ?? '-' }}</p>
            </div>
            <div class="rounded-2xl bg-slate-50 p-5 ring-1 ring-slate-200">
                <p class="text-sm font-medium text-slate-700">Menu</p>
                <p class="mt-2 text-3xl font-semibold text-slate-900">{{ $menusCount ?? '-' }}</p>
            </div>
        </div>

        @php
            $actionMenus = collect($sidebarMenuFlat ?? [])
                ->filter(function ($menu) {
                    $url = trim((string) ($menu->url ?? ''));

                    return $url !== '' && $url !== '#';
                })
                ->unique(fn ($menu) => (string) $menu->url)
                ->values();
        @endphp
    </div>

    <div class="mt-8 grid gap-8 grid-cols-1">
        <!-- Chart Section -->
        <div class="rounded-2xl bg-white p-8 shadow-sm ring-1 ring-slate-200">
            <h2 class="mb-4 text-lg font-semibold text-slate-900">Perbandingan Order (Kemarin vs Hari Ini)</h2>
            <div class="relative h-64 w-full">
                <canvas id="ordersChart"></canvas>
            </div>
        </div>

        <!-- Table Section -->
        <div class="rounded-2xl bg-white p-8 shadow-sm ring-1 ring-slate-200">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-slate-900">Order Hari Ini</h2>
                <span class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-medium text-emerald-800">
                    {{ $todayOrdersCount }} Order
                </span>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 text-xs uppercase text-slate-700">
                        <tr>
                            <th scope="col" class="px-4 py-3 rounded-tl-lg">No. Order</th>
                            <th scope="col" class="px-4 py-3">Pelanggan</th>
                            <th scope="col" class="px-4 py-3">Total</th>
                            <th scope="col" class="px-4 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($todayOrders ?? [] as $order)
                            <tr class="border-b border-slate-100 last:border-0 hover:bg-slate-50">
                                <td class="px-4 py-3 font-medium text-slate-900">
                                    {{ $order->order_number }}
                                </td>
                                <td class="px-4 py-3">
                                    {{ $order->customer->name ?? '-' }}
                                </td>
                                <td class="px-4 py-3">
                                    Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium
                                        @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($order->status === 'process') bg-blue-100 text-blue-800
                                        @elseif($order->status === 'completed') bg-emerald-100 text-emerald-800
                                        @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                        @else bg-slate-100 text-slate-800 @endif">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-center text-slate-500">
                                    Belum ada order hari ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('ordersChart').getContext('2d');
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Kemarin', 'Hari Ini'],
                datasets: [{
                    label: 'Jumlah Order',
                    data: [{{ $yesterdayOrdersCount ?? 0 }}, {{ $todayOrdersCount ?? 0 }}],
                    backgroundColor: 'rgba(206, 38, 38, 0.2)', // #CE2626 (brand color) with opacity
                    borderColor: 'rgb(206, 38, 38)',
                    borderWidth: 2,
                    pointBackgroundColor: 'rgb(206, 38, 38)',
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: 'rgb(206, 38, 38)',
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            precision: 0
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.raw + ' Order';
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
