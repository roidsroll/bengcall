@extends('layouts.user', ['title' => 'Register - bengcall', 'mainClass' => 'mx-auto w-full max-w-6xl px-6 py-10'])

@section('content')
    <div class="mx-auto max-w-xl">
        <div class="rounded-2xl bg-white p-8 shadow-sm ring-1 ring-slate-200">
            <h1 class="text-2xl font-semibold tracking-tight text-slate-900">Register</h1>
            <p class="mt-1 text-sm text-slate-600">Buat akun baru.</p>

            @if ($errors->any())
                <div class="mt-4 rounded-xl bg-red-50 px-4 py-3 text-sm text-red-800 ring-1 ring-red-200">
                    <ul class="list-disc space-y-1 pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register.store') }}" class="mt-6 grid gap-4">
                @csrf

                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <label for="name" class="text-sm font-medium text-slate-700">Nama</label>
                        <input
                            id="name"
                            name="name"
                            type="text"
                            value="{{ old('name') }}"
                            required
                            autofocus
                            class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-slate-900 shadow-sm outline-none focus:border-[#CE2626] focus:ring-4 focus:ring-[#CE2626]/20"
                            placeholder="Nama lengkap"
                        />
                    </div>

                    <div class="sm:col-span-2">
                        <label for="email" class="text-sm font-medium text-slate-700">Email</label>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            value="{{ old('email') }}"
                            required
                            class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-slate-900 shadow-sm outline-none focus:border-[#CE2626] focus:ring-4 focus:ring-[#CE2626]/20"
                            placeholder="you@example.com"
                        />
                    </div>

                    <div>
                        <label for="gender" class="text-sm font-medium text-slate-700">Gender</label>
                        <select
                            id="gender"
                            name="gender"
                            required
                            class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-slate-900 shadow-sm outline-none focus:border-[#CE2626] focus:ring-4 focus:ring-[#CE2626]/20"
                        >
                            <option value="" @selected(old('gender') === null || old('gender') === '') disabled>Pilih…</option>
                            <option value="Laki-laki" @selected(old('gender') === 'Laki-laki')>Laki-laki</option>
                            <option value="Perempuan" @selected(old('gender') === 'Perempuan')>Perempuan</option>
                        </select>
                    </div>

                    <div>
                        <label for="address" class="text-sm font-medium text-slate-700">Alamat (opsional)</label>
                        <input
                            id="address"
                            name="address"
                            type="text"
                            value="{{ old('address') }}"
                            class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-slate-900 shadow-sm outline-none focus:border-[#CE2626] focus:ring-4 focus:ring-[#CE2626]/20"
                            placeholder="Alamat"
                        />
                    </div>

                    <div>
                        <label for="password" class="text-sm font-medium text-slate-700">Password</label>
                        <input
                            id="password"
                            name="password"
                            type="password"
                            required
                            class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-slate-900 shadow-sm outline-none focus:border-[#CE2626] focus:ring-4 focus:ring-[#CE2626]/20"
                            placeholder="••••••••"
                        />
                    </div>

                    <div>
                        <label for="password_confirmation" class="text-sm font-medium text-slate-700">Konfirmasi Password</label>
                        <input
                            id="password_confirmation"
                            name="password_confirmation"
                            type="password"
                            required
                            class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-slate-900 shadow-sm outline-none focus:border-[#CE2626] focus:ring-4 focus:ring-[#CE2626]/20"
                            placeholder="••••••••"
                        />
                    </div>
                </div>

                <button
                    type="submit"
                    class="mt-2 w-full rounded-xl bg-[#CE2626] px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-[#b81f1f] focus:outline-none focus:ring-4 focus:ring-[#CE2626]/30"
                >
                    Buat Akun
                </button>

                <p class="text-center text-sm text-slate-600">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="font-medium text-[#CE2626] hover:underline">Login</a>
                </p>
            </form>
        </div>
    </div>
@endsection
