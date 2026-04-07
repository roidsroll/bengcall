@extends('layouts.admin', ['title' => 'Edit User - bengcall'])

@section('content')
    <div class="mx-auto max-w-2xl">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold tracking-tight text-slate-900">Edit User</h1>
                <p class="mt-1 text-sm text-slate-600">Update data user.</p>
            </div>
            <a
                href="{{ route('admin.users.index') }}"
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

        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="mt-6 space-y-4">
            @csrf
            @method('PUT')

            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <label class="text-sm font-medium text-slate-700" for="name">Nama</label>
                        <input
                            id="name"
                            name="name"
                            type="text"
                            value="{{ old('name', $user->name) }}"
                            required
                            class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-slate-900 shadow-sm outline-none focus:border-[#CE2626] focus:ring-4 focus:ring-[#CE2626]/20"
                        />
                    </div>

                    <div class="sm:col-span-2">
                        <label class="text-sm font-medium text-slate-700" for="email">Email</label>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            value="{{ old('email', $user->email) }}"
                            required
                            class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-slate-900 shadow-sm outline-none focus:border-[#CE2626] focus:ring-4 focus:ring-[#CE2626]/20"
                        />
                    </div>

                    <div>
                        <label class="text-sm font-medium text-slate-700" for="role_id">Role</label>
                        <select
                            id="role_id"
                            name="role_id"
                            required
                            class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-slate-900 shadow-sm outline-none focus:border-[#CE2626] focus:ring-4 focus:ring-[#CE2626]/20"
                        >
                            @foreach ($roles as $role)
                                <option
                                    value="{{ $role->id }}"
                                    @selected((string) old('role_id', (string) $user->role_id) === (string) $role->id)
                                >
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-slate-700" for="gender">Gender</label>
                        <select
                            id="gender"
                            name="gender"
                            required
                            class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-slate-900 shadow-sm outline-none focus:border-[#CE2626] focus:ring-4 focus:ring-[#CE2626]/20"
                        >
                            <option value="Laki-laki" @selected(old('gender', $user->gender) === 'Laki-laki')>Laki-laki</option>
                            <option value="Perempuan" @selected(old('gender', $user->gender) === 'Perempuan')>Perempuan</option>
                        </select>
                    </div>

                    <div class="sm:col-span-2">
                        <label class="text-sm font-medium text-slate-700" for="address">Alamat (opsional)</label>
                        <input
                            id="address"
                            name="address"
                            type="text"
                            value="{{ old('address', $user->address) }}"
                            class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-slate-900 shadow-sm outline-none focus:border-[#CE2626] focus:ring-4 focus:ring-[#CE2626]/20"
                        />
                    </div>

                    <div>
                        <label class="text-sm font-medium text-slate-700" for="password">Password baru (opsional)</label>
                        <input
                            id="password"
                            name="password"
                            type="password"
                            class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-slate-900 shadow-sm outline-none focus:border-[#CE2626] focus:ring-4 focus:ring-[#CE2626]/20"
                        />
                    </div>

                    <div>
                        <label class="text-sm font-medium text-slate-700" for="password_confirmation">Konfirmasi Password</label>
                        <input
                            id="password_confirmation"
                            name="password_confirmation"
                            type="password"
                            class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-slate-900 shadow-sm outline-none focus:border-[#CE2626] focus:ring-4 focus:ring-[#CE2626]/20"
                        />
                    </div>

                    <div class="sm:col-span-2">
                        <label class="inline-flex items-center gap-2 text-sm text-slate-700">
                            <input
                                type="checkbox"
                                name="is_active"
                                value="1"
                                @checked((bool) old('is_active', $user->is_active))
                                class="h-4 w-4 rounded border-slate-300 text-[#CE2626] focus:ring-[#CE2626]/30"
                            />
                            Aktif
                        </label>
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end gap-3">
                    <a
                        href="{{ route('admin.users.index') }}"
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

