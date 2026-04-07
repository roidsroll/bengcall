@extends('layouts.admin', ['title' => 'Manajemen Akses & Role - bengcall'])

@section('content')
    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight text-slate-900">Manajemen Akses & Role</h1>
            <p class="mt-1 text-sm text-slate-600">Assign menu ke role (tabel <span class="font-semibold">menu_roles</span>).</p>
        </div>
    </div>

    <div class="mt-6 overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
        <div class="overflow-x-auto">
            <table class="min-w-full table-fixed divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr class="text-left text-xs font-semibold uppercase tracking-wider text-slate-600">
                        <th class="w-[320px] px-5 py-3">Role</th>
                        <th class="w-[160px] px-5 py-3">Users</th>
                        <th class="w-[220px] px-5 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @forelse ($roles as $role)
                        <tr class="text-sm text-slate-700">
                            <td class="px-5 py-3 align-top">
                                <p class="font-semibold leading-5 text-slate-900">{{ $role->name }}</p>
                            </td>
                            <td class="px-5 py-3 align-top">
                                <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">
                                    {{ $role->users_count }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-right align-top">
                                <a
                                    href="{{ route('admin.access.edit', $role) }}"
                                    class="inline-flex items-center rounded-xl bg-[#CE2626] px-4 py-2 text-xs font-semibold text-white hover:bg-[#b81f1f] focus:outline-none focus:ring-4 focus:ring-[#CE2626]/30"
                                >
                                    Atur Menu
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-5 py-10 text-center text-sm text-slate-600">Tidak ada role.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

