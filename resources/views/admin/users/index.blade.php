@extends('layouts.admin', ['title' => 'Master User - bengcall'])

@section('content')
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight text-slate-900">Master User</h1>
            <p class="mt-1 text-sm text-slate-600">Kelola data user (CRUD).</p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <form method="GET" action="{{ route('admin.users.index') }}" class="flex items-center gap-2">
                <input
                    type="text"
                    name="q"
                    value="{{ $search ?? '' }}"
                    class="w-64 rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm outline-none focus:border-[#CE2626] focus:ring-4 focus:ring-[#CE2626]/20"
                    placeholder="Cari nama / email…"
                />
                <button
                    type="submit"
                    class="rounded-xl bg-white px-4 py-2 text-sm font-semibold text-slate-900 ring-1 ring-slate-200 hover:bg-slate-50"
                >
                    Cari
                </button>
            </form>

            <a
                href="{{ route('admin.users.create') }}"
                class="inline-flex items-center rounded-xl bg-[#CE2626] px-4 py-2 text-sm font-semibold text-white hover:bg-[#b81f1f] focus:outline-none focus:ring-4 focus:ring-[#CE2626]/30"
            >
                + Tambah User
            </a>
        </div>
    </div>

    <div class="mt-6 overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
        <div class="overflow-x-auto">
            <table class="min-w-full table-fixed divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr class="text-left text-xs font-semibold uppercase tracking-wider text-slate-600">
                        <th class="w-[240px] px-5 py-3">Nama</th>
                        <th class="w-[320px] px-5 py-3">Email</th>
                        <th class="w-[140px] px-5 py-3">Role</th>
                        <th class="w-[160px] px-5 py-3">Status</th>
                        <th class="w-[220px] px-5 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @forelse ($users as $user)
                        <tr class="text-sm text-slate-700">
                            <td class="px-5 py-3 align-top">
                                <p class="font-semibold leading-5 text-slate-900">{{ $user->name }}</p>
                                <p class="mt-0.5 text-xs leading-4 text-slate-500">{{ $user->gender }}</p>
                            </td>
                            <td class="px-5 py-3 align-top">
                                <p class="truncate text-slate-700" title="{{ $user->email }}">{{ $user->email }}</p>
                            </td>
                            <td class="px-5 py-3 align-top">
                                <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">
                                    {{ $user->role?->name ?? '-' }}
                                </span>
                            </td>
                            <td class="px-5 py-3 align-top">
                                @if ($user->is_active)
                                    <span class="inline-flex items-center rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-800 ring-1 ring-emerald-200">
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-red-50 px-3 py-1 text-xs font-semibold text-red-800 ring-1 ring-red-200">
                                        Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-right align-top">
                                <div class="flex items-center justify-end gap-2 whitespace-nowrap">
                                    <a
                                        href="{{ route('admin.users.edit', $user) }}"
                                        class="rounded-lg bg-white px-3 py-1.5 text-xs font-semibold text-slate-900 ring-1 ring-slate-200 hover:bg-slate-50"
                                    >
                                        Edit
                                    </a>
                                    <form
                                        method="POST"
                                        action="{{ route('admin.users.destroy', $user) }}"
                                        onsubmit="return confirm('Yakin hapus user ini?')"
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
                            <td colspan="5" class="px-5 py-10 text-center text-sm text-slate-600">
                                Tidak ada data user.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-slate-200 bg-white px-5 py-4">
            {{ $users->links() }}
        </div>
    </div>
@endsection
