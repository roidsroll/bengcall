@extends('layouts.user', ['title' => 'Login - bengcall', 'mainClass' => 'mx-auto w-full max-w-6xl px-6 py-10'])

@section('content')
    <div class="mx-auto max-w-md">
        <div class="rounded-2xl bg-white p-8 shadow-sm ring-1 ring-slate-200">
            <h1 class="text-2xl font-semibold tracking-tight text-slate-900">Login</h1>
            <p class="mt-1 text-sm text-slate-600">Masuk untuk melanjutkan.</p>

            @if ($errors->any())
                <div class="mt-4 rounded-xl bg-red-50 px-4 py-3 text-sm text-red-800 ring-1 ring-red-200">
                    <ul class="list-disc space-y-1 pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login.store') }}" class="mt-6 space-y-4">
                @csrf

                <div>
                    <label for="email" class="text-sm font-medium text-slate-700">Email</label>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-slate-900 shadow-sm outline-none ring-0 placeholder:text-slate-400 focus:border-[#CE2626] focus:ring-4 focus:ring-[#CE2626]/20"
                        placeholder="you@example.com"
                    />
                </div>

                <div>
                    <label for="password" class="text-sm font-medium text-slate-700">Password</label>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        required
                        class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-slate-900 shadow-sm outline-none ring-0 placeholder:text-slate-400 focus:border-[#CE2626] focus:ring-4 focus:ring-[#CE2626]/20"
                        placeholder="••••••••"
                    />
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 text-sm text-slate-700">
                        <input
                            type="checkbox"
                            name="remember"
                            class="h-4 w-4 rounded border-slate-300 text-[#CE2626] focus:ring-[#CE2626]/30"
                        />
                        Remember me
                    </label>
                    <a href="{{ route('register') }}" class="text-sm font-medium text-[#CE2626] hover:underline">
                        Buat akun
                    </a>
                </div>

                <button
                    type="submit"
                    class="w-full rounded-xl bg-[#CE2626] px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-[#b81f1f] focus:outline-none focus:ring-4 focus:ring-[#CE2626]/30"
                >
                    Login
                </button>
            </form>
        </div>
    </div>
@endsection
