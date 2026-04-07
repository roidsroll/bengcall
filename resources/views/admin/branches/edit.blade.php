@extends('layouts.admin', ['title' => 'Edit Branch - bengcall'])

@section('content')
    <div class="mx-auto max-w-3xl">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold tracking-tight text-slate-900">Edit Branch</h1>
                <p class="mt-1 text-sm text-slate-600">Update data branch.</p>
            </div>
            <a
                href="{{ route('admin.branches.index') }}"
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

        <form method="POST" action="{{ route('admin.branches.update', $branch) }}" class="mt-6 space-y-4">
            @csrf
            @method('PUT')

            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="text-sm font-medium text-slate-700" for="branch_code">Branch Code</label>
                        <input
                            id="branch_code"
                            name="branch_code"
                            type="text"
                            value="{{ old('branch_code', $branch->branch_code) }}"
                            required
                            class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-slate-900 shadow-sm outline-none focus:border-[#CE2626] focus:ring-4 focus:ring-[#CE2626]/20"
                        />
                    </div>

                    <div>
                        <label class="text-sm font-medium text-slate-700" for="name">Nama</label>
                        <input
                            id="name"
                            name="name"
                            type="text"
                            value="{{ old('name', $branch->name) }}"
                            required
                            class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-slate-900 shadow-sm outline-none focus:border-[#CE2626] focus:ring-4 focus:ring-[#CE2626]/20"
                        />
                    </div>

                    <div class="sm:col-span-2">
                        <label class="text-sm font-medium text-slate-700" for="address">Alamat</label>
                        <textarea
                            id="address"
                            name="address"
                            rows="3"
                            required
                            class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-slate-900 shadow-sm outline-none focus:border-[#CE2626] focus:ring-4 focus:ring-[#CE2626]/20"
                        >{{ old('address', $branch->address) }}</textarea>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-slate-700" for="phone">Phone (opsional)</label>
                        <input
                            id="phone"
                            name="phone"
                            type="text"
                            value="{{ old('phone', $branch->phone) }}"
                            class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-slate-900 shadow-sm outline-none focus:border-[#CE2626] focus:ring-4 focus:ring-[#CE2626]/20"
                        />
                    </div>

                    <div>
                        <label class="text-sm font-medium text-slate-700" for="manager_id">Manager (opsional)</label>
                        <select
                            id="manager_id"
                            name="manager_id"
                            class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-slate-900 shadow-sm outline-none focus:border-[#CE2626] focus:ring-4 focus:ring-[#CE2626]/20"
                        >
                            <option value="" @selected(old('manager_id', (string) ($branch->manager_id ?? '')) === '')>-</option>
                            @foreach ($managers as $manager)
                                <option
                                    value="{{ $manager->id }}"
                                    @selected((string) old('manager_id', (string) ($branch->manager_id ?? '')) === (string) $manager->id)
                                >
                                    {{ $manager->name }} ({{ $manager->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-slate-700" for="opening_time">Jam Buka</label>
                        <input
                            id="opening_time"
                            name="opening_time"
                            type="time"
                            value="{{ old('opening_time', substr((string) $branch->opening_time, 0, 5)) }}"
                            required
                            class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-slate-900 shadow-sm outline-none focus:border-[#CE2626] focus:ring-4 focus:ring-[#CE2626]/20"
                        />
                    </div>

                    <div>
                        <label class="text-sm font-medium text-slate-700" for="closing_time">Jam Tutup</label>
                        <input
                            id="closing_time"
                            name="closing_time"
                            type="time"
                            value="{{ old('closing_time', substr((string) $branch->closing_time, 0, 5)) }}"
                            required
                            class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-slate-900 shadow-sm outline-none focus:border-[#CE2626] focus:ring-4 focus:ring-[#CE2626]/20"
                        />
                    </div>

                    <div>
                        <label class="text-sm font-medium text-slate-700" for="status">Status</label>
                        <select
                            id="status"
                            name="status"
                            required
                            class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-slate-900 shadow-sm outline-none focus:border-[#CE2626] focus:ring-4 focus:ring-[#CE2626]/20"
                        >
                            @php $status = old('status', $branch->status); @endphp
                            <option value="active" @selected($status === 'active')>active</option>
                            <option value="inactive" @selected($status === 'inactive')>inactive</option>
                            <option value="maintenance" @selected($status === 'maintenance')>maintenance</option>
                        </select>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-slate-700" for="latitude">Latitude (opsional)</label>
                        <input
                            id="latitude"
                            name="latitude"
                            type="text"
                            value="{{ old('latitude', $branch->latitude) }}"
                            class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-slate-900 shadow-sm outline-none focus:border-[#CE2626] focus:ring-4 focus:ring-[#CE2626]/20"
                        />
                    </div>

                    <div>
                        <label class="text-sm font-medium text-slate-700" for="longitude">Longitude (opsional)</label>
                        <input
                            id="longitude"
                            name="longitude"
                            type="text"
                            value="{{ old('longitude', $branch->longitude) }}"
                            class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-slate-900 shadow-sm outline-none focus:border-[#CE2626] focus:ring-4 focus:ring-[#CE2626]/20"
                        />
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end gap-3">
                    <a
                        href="{{ route('admin.branches.index') }}"
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
