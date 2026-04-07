@extends('layouts.admin', ['title' => 'Buat Order - bengcall'])

@section('content')
    <div class="mx-auto max-w-5xl">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold tracking-tight text-slate-900">Buat Order</h1>
                <p class="mt-1 text-sm text-slate-600">Tambah order dengan banyak part & service.</p>
            </div>
            <a
                href="{{ route('admin.orders.index') }}"
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

        @php
            $oldSpareparts = old('spareparts');
            if (! is_array($oldSpareparts) || count($oldSpareparts) < 1) {
                $oldSpareparts = [
                    [
                        'product_id' => null,
                        'quantity' => 1,
                        'price' => 0,
                    ],
                ];
            }

            $oldServices = old('services');
            if (! is_array($oldServices) || count($oldServices) < 1) {
                $oldServices = [
                    [
                        'service_id' => null,
                        'quantity' => 1,
                        'price' => 0,
                    ],
                ];
            }

            $activeTab = (string) old('active_tab', 'sparepart');
            if (! in_array($activeTab, ['sparepart', 'services'], true)) {
                $activeTab = 'sparepart';
            }

            $customerType = (string) old('customer_type', 'customer');
            if (! in_array($customerType, ['walk_in', 'customer'], true)) {
                $customerType = 'customer';
            }
        @endphp

        <form method="POST" action="{{ route('admin.orders.store') }}" class="mt-6 space-y-4">
            @csrf
            <input type="hidden" name="active_tab" id="active_tab" value="{{ $activeTab }}" />
            <input type="hidden" name="customer_type" id="customer_type" value="{{ $customerType }}" />

            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <h2 class="text-lg font-semibold text-slate-900">Info Order</h2>

                <div class="mt-4 flex flex-wrap gap-2 rounded-xl bg-slate-50 p-2 ring-1 ring-slate-200">
                    <button
                        type="button"
                        class="customer-tab-btn rounded-lg px-4 py-2 text-sm font-semibold {{ $customerType === 'walk_in' ? 'bg-white text-slate-900 ring-1 ring-slate-200' : 'text-slate-600 hover:text-slate-900' }}"
                        data-customer-tab="walk_in"
                    >
                        Walk In
                    </button>
                    <button
                        type="button"
                        class="customer-tab-btn rounded-lg px-4 py-2 text-sm font-semibold {{ $customerType === 'customer' ? 'bg-white text-slate-900 ring-1 ring-slate-200' : 'text-slate-600 hover:text-slate-900' }}"
                        data-customer-tab="customer"
                    >
                        Customer
                    </button>
                </div>

                <div class="mt-4 grid gap-4 sm:grid-cols-2">
                    <div class="sm:col-span-2 {{ $customerType === 'walk_in' ? 'hidden' : '' }}" id="customer-tab-customer">
                        <label class="text-sm font-medium text-slate-700" for="user_id">Customer</label>
                        <select
                            id="user_id"
                            name="user_id"
                            {{ $customerType === 'customer' ? 'required' : '' }}
                            class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-slate-900 shadow-sm outline-none focus:border-[#CE2626] focus:ring-4 focus:ring-[#CE2626]/20"
                        >
                            <option value="">- Pilih Customer -</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}" @selected((string) old('user_id') === (string) $customer->id)>
                                    {{ $customer->name }} ({{ $customer->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="sm:col-span-2 {{ $customerType === 'customer' ? 'hidden' : '' }}" id="customer-tab-walk-in">
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div class="sm:col-span-2">
                                <label class="text-sm font-medium text-slate-700" for="walk_in_name">Nama</label>
                                <input
                                    id="walk_in_name"
                                    name="walk_in_name"
                                    type="text"
                                    value="{{ old('walk_in_name') }}"
                                    {{ $customerType === 'walk_in' ? 'required' : '' }}
                                    class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-slate-900 shadow-sm outline-none focus:border-[#CE2626] focus:ring-4 focus:ring-[#CE2626]/20"
                                    placeholder="Nama customer walk in..."
                                />
                            </div>

                            <div>
                                <label class="text-sm font-medium text-slate-700" for="walk_in_call_number">Call Number</label>
                                <input
                                    id="walk_in_call_number"
                                    name="walk_in_call_number"
                                    type="text"
                                    value="{{ old('walk_in_call_number') }}"
                                    {{ $customerType === 'walk_in' ? 'required' : '' }}
                                    class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-slate-900 shadow-sm outline-none focus:border-[#CE2626] focus:ring-4 focus:ring-[#CE2626]/20"
                                    placeholder="08xxxxxxxxxx"
                                />
                            </div>

                            <div>
                                <label class="text-sm font-medium text-slate-700" for="walk_in_address">Address</label>
                                <input
                                    id="walk_in_address"
                                    name="walk_in_address"
                                    type="text"
                                    value="{{ old('walk_in_address') }}"
                                    {{ $customerType === 'walk_in' ? 'required' : '' }}
                                    class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-slate-900 shadow-sm outline-none focus:border-[#CE2626] focus:ring-4 focus:ring-[#CE2626]/20"
                                    placeholder="Alamat customer..."
                                />
                            </div>
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <label class="text-sm font-medium text-slate-700" for="technician_id">Teknisi (opsional, khusus Service)</label>
                        <select
                            id="technician_id"
                            name="technician_id"
                            class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-slate-900 shadow-sm outline-none focus:border-[#CE2626] focus:ring-4 focus:ring-[#CE2626]/20"
                        >
                            <option value="">- Belum di-assign -</option>
                            @foreach ($technicians as $technician)
                                <option value="{{ $technician->id }}" @selected((string) old('technician_id') === (string) $technician->id)>
                                    {{ $technician->name }} ({{ $technician->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-slate-700" for="vehicle_name">Nama Kendaraan</label>
                        <input
                            id="vehicle_name"
                            name="vehicle_name"
                            type="text"
                            value="{{ old('vehicle_name') }}"
                            required
                            class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-slate-900 shadow-sm outline-none focus:border-[#CE2626] focus:ring-4 focus:ring-[#CE2626]/20"
                            placeholder="Contoh: Honda Vario 125"
                        />
                    </div>

                    <div>
                        <label class="text-sm font-medium text-slate-700" for="license_plate">Plat Nomor</label>
                        <input
                            id="license_plate"
                            name="license_plate"
                            type="text"
                            value="{{ old('license_plate') }}"
                            required
                            class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-slate-900 shadow-sm outline-none focus:border-[#CE2626] focus:ring-4 focus:ring-[#CE2626]/20"
                            placeholder="Contoh: B 1234 ABC"
                        />
                    </div>

                    <div class="sm:col-span-2">
                        <label class="text-sm font-medium text-slate-700" for="customer_notes">Keluhan / Catatan (opsional)</label>
                        <textarea
                            id="customer_notes"
                            name="customer_notes"
                            rows="3"
                            class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-slate-900 shadow-sm outline-none focus:border-[#CE2626] focus:ring-4 focus:ring-[#CE2626]/20"
                            placeholder="Tulis keluhan singkat…"
                        >{{ old('customer_notes') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-900">Item Order</h2>
                        <p class="mt-1 text-sm text-slate-600">Pisahkan input Sparepart dan Services.</p>
                    </div>
                </div>

                <div class="mt-4 flex flex-wrap gap-2 rounded-xl bg-slate-50 p-2 ring-1 ring-slate-200">
                    <button
                        type="button"
                        class="tab-btn rounded-lg px-4 py-2 text-sm font-semibold {{ $activeTab === 'sparepart' ? 'bg-white text-slate-900 ring-1 ring-slate-200' : 'text-slate-600 hover:text-slate-900' }}"
                        data-tab="sparepart"
                    >
                        Sparepart
                    </button>
                    <button
                        type="button"
                        class="tab-btn rounded-lg px-4 py-2 text-sm font-semibold {{ $activeTab === 'services' ? 'bg-white text-slate-900 ring-1 ring-slate-200' : 'text-slate-600 hover:text-slate-900' }}"
                        data-tab="services"
                    >
                        Services
                    </button>
                </div>

                <div class="mt-4 {{ $activeTab === 'services' ? 'hidden' : '' }}" id="tab-sparepart">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-semibold text-slate-900">Daftar Sparepart</p>
                        <button
                            type="button"
                            id="add-sparepart"
                            class="rounded-xl bg-white px-4 py-2 text-sm font-semibold text-slate-900 ring-1 ring-slate-200 hover:bg-slate-50"
                        >
                            + Tambah Sparepart
                        </button>
                    </div>

                    <div class="mt-3 overflow-x-auto">
                        <table class="min-w-full table-fixed divide-y divide-slate-200">
                            <thead class="bg-slate-50">
                                <tr class="text-left text-xs font-semibold uppercase tracking-wider text-slate-600">
                                    <th class="w-[420px] px-4 py-3">Sparepart</th>
                                    <th class="w-[120px] px-4 py-3">Qty</th>
                                    <th class="w-[160px] px-4 py-3">Harga</th>
                                    <th class="w-[180px] px-4 py-3">Subtotal</th>
                                    <th class="w-[100px] px-4 py-3 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 bg-white" id="spareparts-body">
                                @foreach ($oldSpareparts as $i => $oldItem)
                                    @php
                                        $oldProductId = $oldItem['product_id'] ?? null;
                                        $oldQty = $oldItem['quantity'] ?? 1;
                                        $oldPrice = $oldItem['price'] ?? 0;
                                    @endphp
                                    <tr class="line-row text-sm text-slate-700" data-index="{{ $i }}">
                                        <td class="px-4 py-3 align-top">
                                            <select
                                                name="spareparts[{{ $i }}][product_id]"
                                                class="line-item mt-0.5 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-slate-900 shadow-sm outline-none focus:border-[#CE2626] focus:ring-4 focus:ring-[#CE2626]/20"
                                            >
                                                <option value="">- Pilih Product -</option>
                                                @foreach ($products as $product)
                                                    <option
                                                        value="{{ $product->id }}"
                                                        data-price="{{ (float) $product->sell_price }}"
                                                        @selected((string) $oldProductId === (string) $product->id)
                                                    >
                                                        {{ $product->name }} ({{ $product->unit }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="px-4 py-3 align-top">
                                            <input
                                                type="number"
                                                min="1"
                                                name="spareparts[{{ $i }}][quantity]"
                                                value="{{ $oldQty }}"
                                                class="line-qty mt-0.5 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-slate-900 shadow-sm outline-none focus:border-[#CE2626] focus:ring-4 focus:ring-[#CE2626]/20"
                                            />
                                        </td>
                                        <td class="px-4 py-3 align-top">
                                            <input
                                                type="number"
                                                min="0"
                                                step="0.01"
                                                name="spareparts[{{ $i }}][price]"
                                                value="{{ $oldPrice }}"
                                                class="line-price mt-0.5 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-slate-900 shadow-sm outline-none focus:border-[#CE2626] focus:ring-4 focus:ring-[#CE2626]/20"
                                            />
                                        </td>
                                        <td class="px-4 py-3 align-top">
                                            <p class="line-subtotal mt-2 font-semibold text-slate-900">0</p>
                                        </td>
                                        <td class="px-4 py-3 text-right align-top">
                                            <button
                                                type="button"
                                                class="remove-line rounded-lg bg-[#CE2626] px-3 py-1.5 text-xs font-semibold text-white hover:bg-[#b81f1f]"
                                            >
                                                Hapus
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mt-4 {{ $activeTab === 'sparepart' ? 'hidden' : '' }}" id="tab-services">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-semibold text-slate-900">Daftar Services</p>
                        <button
                            type="button"
                            id="add-service"
                            class="rounded-xl bg-white px-4 py-2 text-sm font-semibold text-slate-900 ring-1 ring-slate-200 hover:bg-slate-50"
                        >
                            + Tambah Service
                        </button>
                    </div>

                    <div class="mt-3 overflow-x-auto">
                        <table class="min-w-full table-fixed divide-y divide-slate-200">
                            <thead class="bg-slate-50">
                                <tr class="text-left text-xs font-semibold uppercase tracking-wider text-slate-600">
                                    <th class="w-[420px] px-4 py-3">Service</th>
                                    <th class="w-[120px] px-4 py-3">Qty</th>
                                    <th class="w-[160px] px-4 py-3">Harga</th>
                                    <th class="w-[180px] px-4 py-3">Subtotal</th>
                                    <th class="w-[100px] px-4 py-3 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 bg-white" id="services-body">
                                @foreach ($oldServices as $i => $oldItem)
                                    @php
                                        $oldServiceId = $oldItem['service_id'] ?? null;
                                        $oldQty = $oldItem['quantity'] ?? 1;
                                        $oldPrice = $oldItem['price'] ?? 0;
                                    @endphp
                                    <tr class="line-row text-sm text-slate-700" data-index="{{ $i }}">
                                        <td class="px-4 py-3 align-top">
                                            <select
                                                name="services[{{ $i }}][service_id]"
                                                class="line-item mt-0.5 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-slate-900 shadow-sm outline-none focus:border-[#CE2626] focus:ring-4 focus:ring-[#CE2626]/20"
                                            >
                                                <option value="">- Pilih Service -</option>
                                                @foreach ($services as $service)
                                                    <option
                                                        value="{{ $service->id }}"
                                                        data-price="{{ (float) $service->base_price }}"
                                                        @selected((string) $oldServiceId === (string) $service->id)
                                                    >
                                                        {{ $service->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="px-4 py-3 align-top">
                                            <input
                                                type="number"
                                                min="1"
                                                name="services[{{ $i }}][quantity]"
                                                value="{{ $oldQty }}"
                                                class="line-qty mt-0.5 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-slate-900 shadow-sm outline-none focus:border-[#CE2626] focus:ring-4 focus:ring-[#CE2626]/20"
                                            />
                                        </td>
                                        <td class="px-4 py-3 align-top">
                                            <input
                                                type="number"
                                                min="0"
                                                step="0.01"
                                                name="services[{{ $i }}][price]"
                                                value="{{ $oldPrice }}"
                                                class="line-price mt-0.5 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-slate-900 shadow-sm outline-none focus:border-[#CE2626] focus:ring-4 focus:ring-[#CE2626]/20"
                                            />
                                        </td>
                                        <td class="px-4 py-3 align-top">
                                            <p class="line-subtotal mt-2 font-semibold text-slate-900">0</p>
                                        </td>
                                        <td class="px-4 py-3 text-right align-top">
                                            <button
                                                type="button"
                                                class="remove-line rounded-lg bg-[#CE2626] px-3 py-1.5 text-xs font-semibold text-white hover:bg-[#b81f1f]"
                                            >
                                                Hapus
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div class="text-sm text-slate-600">
                        <p>
                            Total Sparepart:
                            <span class="ml-2 text-lg font-semibold text-slate-900" id="sparepart-total-display">0</span>
                        </p>
                        <p class="mt-1">
                            Total Service:
                            <span class="ml-2 text-lg font-semibold text-slate-900" id="service-total-display">0</span>
                        </p>
                        <p class="mt-1">
                            Grand Total:
                            <span class="ml-2 text-lg font-semibold text-slate-900" id="grand-total-display">0</span>
                        </p>
                    </div>

                    <div class="flex items-center justify-end gap-3">
                        <a
                            href="{{ route('admin.orders.index') }}"
                            class="rounded-xl bg-white px-4 py-2 text-sm font-semibold text-slate-900 ring-1 ring-slate-200 hover:bg-slate-50"
                        >
                            Batal
                        </a>
                        <button
                            type="submit"
                            class="rounded-xl bg-[#CE2626] px-4 py-2 text-sm font-semibold text-white hover:bg-[#b81f1f] focus:outline-none focus:ring-4 focus:ring-[#CE2626]/30"
                        >
                            Simpan Order
                        </button>
                    </div>
                </div>
            </div>

            <template id="sparepart-template">
                <tr class="line-row text-sm text-slate-700" data-index="__INDEX__">
                    <td class="px-4 py-3 align-top">
                        <select
                            name="spareparts[__INDEX__][product_id]"
                            class="line-item mt-0.5 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-slate-900 shadow-sm outline-none focus:border-[#CE2626] focus:ring-4 focus:ring-[#CE2626]/20"
                        >
                            <option value="">- Pilih Product -</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}" data-price="{{ (float) $product->sell_price }}">
                                    {{ $product->name }} ({{ $product->unit }})
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td class="px-4 py-3 align-top">
                        <input
                            type="number"
                            min="1"
                            name="spareparts[__INDEX__][quantity]"
                            value="1"
                            class="line-qty mt-0.5 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-slate-900 shadow-sm outline-none focus:border-[#CE2626] focus:ring-4 focus:ring-[#CE2626]/20"
                        />
                    </td>
                    <td class="px-4 py-3 align-top">
                        <input
                            type="number"
                            min="0"
                            step="0.01"
                            name="spareparts[__INDEX__][price]"
                            value="0"
                            class="line-price mt-0.5 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-slate-900 shadow-sm outline-none focus:border-[#CE2626] focus:ring-4 focus:ring-[#CE2626]/20"
                        />
                    </td>
                    <td class="px-4 py-3 align-top">
                        <p class="line-subtotal mt-2 font-semibold text-slate-900">0</p>
                    </td>
                    <td class="px-4 py-3 text-right align-top">
                        <button
                            type="button"
                            class="remove-line rounded-lg bg-[#CE2626] px-3 py-1.5 text-xs font-semibold text-white hover:bg-[#b81f1f]"
                        >
                            Hapus
                        </button>
                    </td>
                </tr>
            </template>

            <template id="service-template">
                <tr class="line-row text-sm text-slate-700" data-index="__INDEX__">
                    <td class="px-4 py-3 align-top">
                        <select
                            name="services[__INDEX__][service_id]"
                            class="line-item mt-0.5 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-slate-900 shadow-sm outline-none focus:border-[#CE2626] focus:ring-4 focus:ring-[#CE2626]/20"
                        >
                            <option value="">- Pilih Service -</option>
                            @foreach ($services as $service)
                                <option value="{{ $service->id }}" data-price="{{ (float) $service->base_price }}">
                                    {{ $service->name }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td class="px-4 py-3 align-top">
                        <input
                            type="number"
                            min="1"
                            name="services[__INDEX__][quantity]"
                            value="1"
                            class="line-qty mt-0.5 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-slate-900 shadow-sm outline-none focus:border-[#CE2626] focus:ring-4 focus:ring-[#CE2626]/20"
                        />
                    </td>
                    <td class="px-4 py-3 align-top">
                        <input
                            type="number"
                            min="0"
                            step="0.01"
                            name="services[__INDEX__][price]"
                            value="0"
                            class="line-price mt-0.5 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-slate-900 shadow-sm outline-none focus:border-[#CE2626] focus:ring-4 focus:ring-[#CE2626]/20"
                        />
                    </td>
                    <td class="px-4 py-3 align-top">
                        <p class="line-subtotal mt-2 font-semibold text-slate-900">0</p>
                    </td>
                    <td class="px-4 py-3 text-right align-top">
                        <button
                            type="button"
                            class="remove-line rounded-lg bg-[#CE2626] px-3 py-1.5 text-xs font-semibold text-white hover:bg-[#b81f1f]"
                        >
                            Hapus
                        </button>
                    </td>
                </tr>
            </template>
        </form>
    </div>

    <script>
        (function () {
            const activeTabInput = document.getElementById('active_tab');
            const tabButtons = document.querySelectorAll('.tab-btn');
            const sparepartTab = document.getElementById('tab-sparepart');
            const servicesTab = document.getElementById('tab-services');

            const sparepartsBody = document.getElementById('spareparts-body');
            const servicesBody = document.getElementById('services-body');
            const addSparepartButton = document.getElementById('add-sparepart');
            const addServiceButton = document.getElementById('add-service');
            const sparepartTemplate = document.getElementById('sparepart-template');
            const serviceTemplate = document.getElementById('service-template');

            const sparepartTotalDisplay = document.getElementById('sparepart-total-display');
            const serviceTotalDisplay = document.getElementById('service-total-display');
            const grandTotalDisplay = document.getElementById('grand-total-display');

            function toNumber(value) {
                const parsed = Number(value);
                return Number.isFinite(parsed) ? parsed : 0;
            }

            function formatIDR(value) {
                try {
                    return new Intl.NumberFormat('id-ID', { maximumFractionDigits: 0 }).format(value);
                } catch (e) {
                    return String(Math.round(value));
                }
            }

            function setActiveTab(tab) {
                const value = tab === 'services' ? 'services' : 'sparepart';

                if (activeTabInput) activeTabInput.value = value;

                sparepartTab?.classList.toggle('hidden', value !== 'sparepart');
                servicesTab?.classList.toggle('hidden', value !== 'services');

                tabButtons.forEach((btn) => {
                    const btnTab = btn.getAttribute('data-tab');
                    const isActive = btnTab === value;
                    btn.classList.toggle('bg-white', isActive);
                    btn.classList.toggle('text-slate-900', isActive);
                    btn.classList.toggle('ring-1', isActive);
                    btn.classList.toggle('ring-slate-200', isActive);
                    btn.classList.toggle('text-slate-600', !isActive);
                });
            }

            function applyDefaultPrice(row) {
                const priceEl = row.querySelector('.line-price');
                const selectEl = row.querySelector('.line-item');
                if (!priceEl || !selectEl) return;

                const option = selectEl.options[selectEl.selectedIndex];
                const price = option ? toNumber(option.dataset.price) : 0;
                if (price > 0) priceEl.value = String(price);
            }

            function updateRowSubtotal(row) {
                const qty = toNumber(row.querySelector('.line-qty')?.value);
                const price = toNumber(row.querySelector('.line-price')?.value);
                const subtotal = qty * price;

                const subtotalEl = row.querySelector('.line-subtotal');
                if (subtotalEl) subtotalEl.textContent = formatIDR(subtotal);

                return subtotal;
            }

            function updateTotalFor(body, display) {
                let total = 0;
                body?.querySelectorAll('.line-row').forEach((row) => {
                    total += updateRowSubtotal(row);
                });
                if (display) display.textContent = formatIDR(total);
                return total;
            }

            function updateAllTotals() {
                const sparepartTotal = updateTotalFor(sparepartsBody, sparepartTotalDisplay);
                const serviceTotal = updateTotalFor(servicesBody, serviceTotalDisplay);
                if (grandTotalDisplay) grandTotalDisplay.textContent = formatIDR(sparepartTotal + serviceTotal);
            }

            function nextIndex(body) {
                const rows = Array.from(body?.querySelectorAll('.line-row') ?? []);
                const maxIndex = rows.reduce((max, row) => {
                    const idx = Number(row.dataset.index);
                    return Number.isFinite(idx) ? Math.max(max, idx) : max;
                }, -1);
                return maxIndex + 1;
            }

            function addRow(body, template) {
                if (!body || !template) return;

                const index = nextIndex(body);
                const html = template.innerHTML.replaceAll('__INDEX__', String(index));
                const wrapper = document.createElement('tbody');
                wrapper.innerHTML = html.trim();
                const row = wrapper.querySelector('tr');
                if (!row) return;
                body.appendChild(row);
                updateAllTotals();
            }

            function setupTable(body, template, addButton) {
                addButton?.addEventListener('click', () => addRow(body, template));

                body?.addEventListener('change', (event) => {
                    const target = event.target;
                    if (!(target instanceof HTMLElement)) return;
                    const row = target.closest('.line-row');
                    if (!row) return;

                    if (target.classList.contains('line-item')) {
                        applyDefaultPrice(row);
                    }

                    updateAllTotals();
                });

                body?.addEventListener('input', (event) => {
                    const target = event.target;
                    if (!(target instanceof HTMLElement)) return;
                    if (!target.classList.contains('line-qty') && !target.classList.contains('line-price')) return;
                    updateAllTotals();
                });

                body?.addEventListener('click', (event) => {
                    const target = event.target;
                    if (!(target instanceof HTMLElement)) return;
                    if (!target.classList.contains('remove-line')) return;
                    const row = target.closest('.line-row');
                    if (row) row.remove();
                    if ((body?.querySelectorAll('.line-row')?.length ?? 0) < 1) {
                        addRow(body, template);
                    }
                    updateAllTotals();
                });
            }

            tabButtons.forEach((btn) => {
                btn.addEventListener('click', () => {
                    setActiveTab(btn.getAttribute('data-tab') || 'sparepart');
                });
            });

            setupTable(sparepartsBody, sparepartTemplate, addSparepartButton);
            setupTable(servicesBody, serviceTemplate, addServiceButton);

            setActiveTab(activeTabInput?.value || 'sparepart');
            updateAllTotals();

            const customerTypeInput = document.getElementById('customer_type');
            const customerTabButtons = document.querySelectorAll('.customer-tab-btn');
            const customerTabCustomer = document.getElementById('customer-tab-customer');
            const customerTabWalkIn = document.getElementById('customer-tab-walk-in');
            const customerSelect = document.getElementById('user_id');
            const walkInName = document.getElementById('walk_in_name');
            const walkInCallNumber = document.getElementById('walk_in_call_number');
            const walkInAddress = document.getElementById('walk_in_address');

            function setCustomerType(value) {
                if (value !== 'walk_in' && value !== 'customer') value = 'customer';
                if (customerTypeInput) customerTypeInput.value = value;

                const isWalkIn = value === 'walk_in';
                customerTabCustomer?.classList.toggle('hidden', isWalkIn);
                customerTabWalkIn?.classList.toggle('hidden', !isWalkIn);

                if (customerSelect instanceof HTMLSelectElement) customerSelect.required = !isWalkIn;
                if (walkInName instanceof HTMLInputElement) walkInName.required = isWalkIn;
                if (walkInCallNumber instanceof HTMLInputElement) walkInCallNumber.required = isWalkIn;
                if (walkInAddress instanceof HTMLInputElement) walkInAddress.required = isWalkIn;

                customerTabButtons.forEach((btn) => {
                    const btnTab = btn.getAttribute('data-customer-tab');
                    const active = btnTab === value;
                    btn.classList.toggle('bg-white', active);
                    btn.classList.toggle('text-slate-900', active);
                    btn.classList.toggle('ring-1', active);
                    btn.classList.toggle('ring-slate-200', active);
                    btn.classList.toggle('text-slate-600', !active);
                });
            }

            customerTabButtons.forEach((btn) => {
                btn.addEventListener('click', () => {
                    setCustomerType(btn.getAttribute('data-customer-tab') || 'customer');
                });
            });

            setCustomerType(customerTypeInput?.value || 'customer');
        })();
    </script>
@endsection
