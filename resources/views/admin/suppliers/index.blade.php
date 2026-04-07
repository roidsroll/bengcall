@extends('layouts.admin', ['title' => 'Master Suppliers - bengcall'])

@section('content')
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight text-slate-900">Master Suppliers</h1>
            <p class="mt-1 text-sm text-slate-600">CRUD untuk data master supplier.</p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <form method="GET" action="{{ route('admin.suppliers.index') }}" class="flex items-center gap-2">
                <input
                    type="text"
                    name="q"
                    value="{{ $search ?? '' }}"
                    class="w-64 rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm outline-none focus:border-[#CE2626] focus:ring-4 focus:ring-[#CE2626]/20"
                    placeholder="Cari kode / nama / teleponâ€¦"
                />
                <button
                    type="submit"
                    class="rounded-xl bg-white px-4 py-2 text-sm font-semibold text-slate-900 ring-1 ring-slate-200 hover:bg-slate-50"
                >
                    Cari
                </button>
            </form>

            <a
                href="{{ route('admin.suppliers.create') }}"
                class="inline-flex items-center rounded-xl bg-[#CE2626] px-4 py-2 text-sm font-semibold text-white hover:bg-[#b81f1f] focus:outline-none focus:ring-4 focus:ring-[#CE2626]/30"
            >
                + Tambah Supplier
            </a>
        </div>
    </div>

    <div class="mt-6 overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
        <div class="overflow-x-auto">
            <table class="min-w-full table-fixed divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr class="text-left text-xs font-semibold uppercase tracking-wider text-slate-600">
                        <th class="w-[200px] px-5 py-3">Kode</th>
                        <th class="w-[280px] px-5 py-3">Nama</th>
                        <th class="w-[220px] px-5 py-3">Telepon</th>
                        <th class="w-[320px] px-5 py-3">Alamat</th>
                        <th class="w-[140px] px-5 py-3">Status</th>
                        <th class="w-[220px] px-5 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @forelse ($suppliers as $supplier)
                        <tr class="text-sm text-slate-700">
                            <td class="px-5 py-3 align-top">
                                <p class="font-mono font-semibold text-slate-900">{{ $supplier->code }}</p>
                            </td>
                            <td class="px-5 py-3 align-top">
                                <p class="font-semibold text-slate-900">{{ $supplier->name }}</p>
                            </td>
                            <td class="px-5 py-3 align-top">{{ $supplier->phone ?: '-' }}</td>
                            <td class="px-5 py-3 align-top">
                                <p class="line-clamp-2 text-slate-700">{{ $supplier->address ?: '-' }}</p>
                            </td>
                            <td class="px-5 py-3 align-top">
                                @if ($supplier->is_active)
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
                                        href="{{ route('admin.suppliers.edit', $supplier) }}"
                                        class="rounded-lg bg-white px-3 py-1.5 text-xs font-semibold text-slate-900 ring-1 ring-slate-200 hover:bg-slate-50"
                                    >
                                        Edit
                                    </a>
                                    <form
                                        method="POST"
                                        action="{{ route('admin.suppliers.destroy', $supplier) }}"
                                        onsubmit="return confirm('Yakin hapus supplier ini?')"
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
                                Tidak ada data supplier.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-slate-200 bg-white px-5 py-4">
            {{ $suppliers->links() }}
        </div>
    </div>
@endsection

