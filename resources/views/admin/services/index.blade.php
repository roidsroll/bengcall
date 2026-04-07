@extends('layouts.admin', ['title' => 'Master Services - bengcall'])

@section('content')
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight text-slate-900">Master Services</h1>
            <p class="mt-1 text-sm text-slate-600">CRUD untuk data service.</p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <form method="GET" action="{{ route('admin.services.index') }}" class="flex items-center gap-2">
                <input
                    type="text"
                    name="q"
                    value="{{ $search ?? '' }}"
                    class="w-64 rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm outline-none focus:border-[#CE2626] focus:ring-4 focus:ring-[#CE2626]/20"
                    placeholder="Cari nama / slug / categoryâ€¦"
                />
                <button
                    type="submit"
                    class="rounded-xl bg-white px-4 py-2 text-sm font-semibold text-slate-900 ring-1 ring-slate-200 hover:bg-slate-50"
                >
                    Cari
                </button>
            </form>

            <a
                href="{{ route('admin.services.create') }}"
                class="inline-flex items-center rounded-xl bg-[#CE2626] px-4 py-2 text-sm font-semibold text-white hover:bg-[#b81f1f] focus:outline-none focus:ring-4 focus:ring-[#CE2626]/30"
            >
                + Tambah Service
            </a>
        </div>
    </div>

    <div class="mt-6 overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
        <div class="overflow-x-auto">
            <table class="min-w-full table-fixed divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr class="text-left text-xs font-semibold uppercase tracking-wider text-slate-600">
                        <th class="w-[320px] px-5 py-3">Nama</th>
                        <th class="w-[240px] px-5 py-3">Category</th>
                        <th class="w-[180px] px-5 py-3">Harga Dasar</th>
                        <th class="w-[180px] px-5 py-3">Estimasi</th>
                        <th class="w-[140px] px-5 py-3">Status</th>
                        <th class="w-[220px] px-5 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @forelse ($services as $service)
                        <tr class="text-sm text-slate-700">
                            <td class="px-5 py-3 align-top">
                                <p class="font-semibold leading-5 text-slate-900">{{ $service->name }}</p>
                                <p class="mt-1 text-xs text-slate-600">
                                    Slug: <span class="font-mono">{{ $service->slug }}</span>
                                </p>
                            </td>
                            <td class="px-5 py-3 align-top">{{ $service->category }}</td>
                            <td class="px-5 py-3 align-top">{{ number_format((float) $service->base_price, 0, ',', '.') }}</td>
                            <td class="px-5 py-3 align-top">{{ (int) $service->estimated_duration }} menit</td>
                            <td class="px-5 py-3 align-top">
                                @if ($service->is_active)
                                    <span class="inline-flex items-center rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-800 ring-1 ring-emerald-200">
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700 ring-1 ring-slate-200">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-right align-top">
                                <div class="flex items-center justify-end gap-2 whitespace-nowrap">
                                    <a
                                        href="{{ route('admin.services.edit', $service) }}"
                                        class="rounded-lg bg-white px-3 py-1.5 text-xs font-semibold text-slate-900 ring-1 ring-slate-200 hover:bg-slate-50"
                                    >
                                        Edit
                                    </a>
                                    <form
                                        method="POST"
                                        action="{{ route('admin.services.destroy', $service) }}"
                                        onsubmit="return confirm('Yakin hapus service ini?')"
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
                            <td colspan="6" class="px-5 py-10 text-center text-sm text-slate-600">
                                Tidak ada data service.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-slate-200 bg-white px-5 py-4">
            {{ $services->links() }}
        </div>
    </div>
@endsection

