<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? config('app.name', 'bengcall') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        @php
            $tailwindBuilt = file_exists(public_path('css/app.css')) && filesize(public_path('css/app.css')) > 0;
            $mainClass = $mainClass ?? 'mx-auto w-full max-w-6xl px-6 py-10';
        @endphp

        @if ($tailwindBuilt)
            <link rel="stylesheet" href="/css/app.css?v={{ filemtime(public_path('css/app.css')) }}">
        @else
            <style>
                :root { color-scheme: light dark; }
                body { font-family: "Instrument Sans", ui-sans-serif, system-ui, sans-serif; margin: 0; }
                .tw-missing { max-width: 64rem; margin: 1.25rem auto; padding: 0 1rem; }
                .tw-card { border: 1px solid rgba(100,116,139,.35); border-radius: 14px; padding: 16px; }
                .tw-title { font-size: 18px; font-weight: 700; margin: 0 0 6px; }
                .tw-text { margin: 0; color: rgba(100,116,139,1); }
                .tw-code { font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace; }
            </style>
        @endif
    </head>
    <body class="min-h-screen bg-slate-50 text-slate-900">
        @if (! $tailwindBuilt)
            <div class="tw-missing">
                <div class="tw-card">
                    <p class="tw-title">Tailwind belum kebuild</p>
                    <p class="tw-text">
                        File <span class="tw-code">public/css/app.css</span> belum ada / masih kosong.
                        Jalankan <span class="tw-code">npm.cmd install</span> lalu <span class="tw-code">npm.cmd run dev</span> atau <span class="tw-code">npm.cmd run build</span>.
                    </p>
                </div>
            </div>
        @endif

        <header class="bg-[#CE2626] text-white">
            <div class="mx-auto flex max-w-6xl items-center justify-between px-6 py-4">
                <a href="{{ route('home') }}" class="text-lg font-semibold tracking-tight">bengcall</a>

                <nav class="flex items-center gap-2 text-sm font-medium">
                    <a href="{{ route('home') }}" class="rounded-lg px-3 py-2 hover:bg-white/10">Home</a>

                    @auth
                        <a href="{{ route('dashboard') }}" class="rounded-lg px-3 py-2 hover:bg-white/10">
                            Dashboard
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="rounded-lg px-3 py-2 hover:bg-white/10">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="rounded-lg px-3 py-2 hover:bg-white/10">Login</a>
                        <a href="{{ route('register') }}" class="rounded-lg bg-white/10 px-3 py-2 hover:bg-white/20">
                            Register
                        </a>
                    @endauth
                </nav>
            </div>
        </header>

        <main class="{{ $mainClass }}">
            @if (session('status'))
                <div class="mb-6 rounded-xl bg-emerald-50 px-4 py-3 text-sm text-emerald-800 ring-1 ring-emerald-200">
                    {{ session('status') }}
                </div>
            @endif

            @yield('content')
        </main>

        <footer class="border-t border-slate-200 bg-white">
            <div class="mx-auto flex max-w-6xl flex-col gap-2 px-6 py-6 text-sm text-slate-600 sm:flex-row sm:items-center sm:justify-between">
                <p>© {{ date('Y') }} bengcall</p>
                <p class="text-slate-500">Bengkel panggilan cepat & terpercaya</p>
            </div>
        </footer>
    </body>
</html>
