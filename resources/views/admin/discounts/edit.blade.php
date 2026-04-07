@extends('layouts.admin', ['title' => 'Edit Discount - bengcall'])

@section('content')
    <div class="mx-auto max-w-3xl">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold tracking-tight text-slate-900">Edit Discount</h1>
                <p class="mt-1 text-sm text-slate-600">Update data master discount / kode promo.</p>
            </div>
            <a
                href="{{ route('admin.discounts.index') }}"
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

        <form method="POST" action="{{ route('admin.discounts.update', $discount) }}" class="mt-6 space-y-4">
            @csrf
            @method('PUT')

            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <label class="text-sm font-medium text-slate-700" for="discount_code">Kode Discount</label>
                        <input
                            id="discount_code"
                            name="discount_code"
                            type="text"
                            value="{{ old('discount_code', $discount->discount_code) }}"
                            required
                            class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 font-mono text-slate-900 shadow-sm outline-none focus:border-[#CE2626] focus:ring-4 focus:ring-[#CE2626]/20"
                        />
                    </div>

                    <div class="sm:col-span-2">
                        <label class="text-sm font-medium text-slate-700" for="name">Nama Program</label>
                        <input
                            id="name"
                            name="name"
                            type="text"
                            value="{{ old('name', $discount->name) }}"
                            required
                            class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-slate-900 shadow-sm outline-none focus:border-[#CE2626] focus:ring-4 focus:ring-[#CE2626]/20"
                        />
                    </div>

                    <div class="sm:col-span-2">
                        <label class="text-sm font-medium text-slate-700" for="description">Deskripsi (opsional)</label>
                        <textarea
                            id="description"
                            name="description"
                            rows="3"
                            class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-slate-900 shadow-sm outline-none focus:border-[#CE2626] focus:ring-4 focus:ring-[#CE2626]/20"
                        >{{ old('description', $discount->description) }}</textarea>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-slate-700" for="type">Tipe</label>
                        <select
                            id="type"
                            name="type"
                            required
                            class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-slate-900 shadow-sm outline-none focus:border-[#CE2626] focus:ring-4 focus:ring-[#CE2626]/20"
                        >
                            <option value="percentage" @selected(old('type', $discount->type) === 'percentage')>Percentage (persen)</option>
                            <option value="fixed" @selected(old('type', $discount->type) === 'fixed')>Fixed (nominal)</option>
                        </select>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-slate-700" for="value">Nilai</label>
                        <input
                            id="value"
                            name="value"
                            type="number"
                            min="0"
                            step="0.01"
                            value="{{ old('value', $discount->value) }}"
                            required
                            class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-slate-900 shadow-sm outline-none focus:border-[#CE2626] focus:ring-4 focus:ring-[#CE2626]/20"
                        />
                    </div>

                    <div>
                        <label class="text-sm font-medium text-slate-700" for="min_purchase">Min Purchase</label>
                        <input
                            id="min_purchase"
                            name="min_purchase"
                            type="number"
                            min="0"
                            step="0.01"
                            value="{{ old('min_purchase', $discount->min_purchase) }}"
                            class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-slate-900 shadow-sm outline-none focus:border-[#CE2626] focus:ring-4 focus:ring-[#CE2626]/20"
                        />
                    </div>

                    <div>
                        <label class="text-sm font-medium text-slate-700" for="max_discount">Max Discount (opsional)</label>
                        <input
                            id="max_discount"
                            name="max_discount"
                            type="number"
                            min="0"
                            step="0.01"
                            value="{{ old('max_discount', $discount->max_discount) }}"
                            class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-slate-900 shadow-sm outline-none focus:border-[#CE2626] focus:ring-4 focus:ring-[#CE2626]/20"
                        />
                    </div>

                    <div>
                        <label class="text-sm font-medium text-slate-700" for="start_date">Start Date (opsional)</label>
                        <input
                            id="start_date"
                            name="start_date"
                            type="date"
                            value="{{ old('start_date', $discount->start_date?->format('Y-m-d')) }}"
                            class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-slate-900 shadow-sm outline-none focus:border-[#CE2626] focus:ring-4 focus:ring-[#CE2626]/20"
                        />
                    </div>

                    <div>
                        <label class="text-sm font-medium text-slate-700" for="end_date">End Date (opsional)</label>
                        <input
                            id="end_date"
                            name="end_date"
                            type="date"
                            value="{{ old('end_date', $discount->end_date?->format('Y-m-d')) }}"
                            class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-slate-900 shadow-sm outline-none focus:border-[#CE2626] focus:ring-4 focus:ring-[#CE2626]/20"
                        />
                    </div>

                    <div>
                        <label class="text-sm font-medium text-slate-700" for="quota">Quota</label>
                        <input
                            id="quota"
                            name="quota"
                            type="number"
                            value="{{ old('quota', $discount->quota) }}"
                            required
                            class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-slate-900 shadow-sm outline-none focus:border-[#CE2626] focus:ring-4 focus:ring-[#CE2626]/20"
                        />
                        <p class="mt-1 text-xs text-slate-500">-1 = tidak terbatas.</p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-slate-700">Used Count</label>
                        <input
                            type="text"
                            value="{{ (int) $discount->used_count }}"
                            disabled
                            class="mt-1 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-slate-900 shadow-sm"
                        />
                        <p class="mt-1 text-xs text-slate-500">Di-update oleh transaksi, bukan dari master.</p>
                    </div>

                    <div class="sm:col-span-2">
                        <label class="inline-flex items-center gap-2 text-sm text-slate-700">
                            <input
                                type="checkbox"
                                name="is_active"
                                value="1"
                                @checked((bool) old('is_active', $discount->is_active))
                                class="h-4 w-4 rounded border-slate-300 text-[#CE2626] focus:ring-[#CE2626]/30"
                            />
                            Aktif
                        </label>
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end gap-3">
                    <a
                        href="{{ route('admin.discounts.index') }}"
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

