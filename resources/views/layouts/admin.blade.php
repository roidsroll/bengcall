<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? 'Admin - bengcall' }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">

        @php
            $tailwindBuilt = file_exists(public_path('css/app.css')) && filesize(public_path('css/app.css')) > 0;
        @endphp

        @if ($tailwindBuilt)
            <link rel="stylesheet" href="/css/app.css?v={{ filemtime(public_path('css/app.css')) }}">
        @else
            <style>
                :root { color-scheme: light dark; }
                body { font-family: "Instrument Sans", ui-sans-serif, system-ui, sans-serif; margin: 0; }
            </style>
        @endif
    </head>
    <body class="min-h-screen bg-slate-50 text-slate-900">
        <div class="min-h-screen lg:flex">
            <aside class="w-full bg-[#CE2626] text-white lg:w-72">
                <div class="flex items-center justify-between px-6 py-5 lg:justify-start">
                    <a href="{{ route('admin.dashboard') }}" class="text-lg font-semibold tracking-tight">bengcall</a>
                    <span class="rounded-full bg-white/15 px-3 py-1 text-xs font-semibold">
                        {{ strtoupper(auth()->user()?->role?->name ?? 'ADMIN') }}
                    </span>
                </div>

                <nav class="px-3 pb-6">
                    @php
                        $currentPath = (string) request()->path();
                        $activeMenuUrlNormalized = null;
                        $activeMenuUrlLength = -1;

                        foreach (($sidebarMenuFlat ?? []) as $menu) {
                            $rawUrl = trim((string) ($menu->url ?? ''));

                            if ($rawUrl === '' || $rawUrl === '#') {
                                continue;
                            }

                            if (str_starts_with($rawUrl, 'http://') || str_starts_with($rawUrl, 'https://')) {
                                continue;
                            }

                            $normalized = \App\Models\Menu::normalizeInternalPath($rawUrl);

                            if ($normalized === '') {
                                continue;
                            }

                            if ($currentPath !== $normalized && ! str_starts_with($currentPath, $normalized.'/')) {
                                continue;
                            }

                            $len = strlen($normalized);

                            if ($len > $activeMenuUrlLength) {
                                $activeMenuUrlNormalized = $normalized;
                                $activeMenuUrlLength = $len;
                            }
                        }
                    @endphp

                    @forelse (($sidebarMenus ?? []) as $node)
                        @include('layouts.partials.admin-sidebar-item', [
                            'node' => $node,
                            'level' => 0,
                            'activeMenuUrlNormalized' => $activeMenuUrlNormalized,
                        ])
                    @empty
                        <p class="px-4 py-2 text-sm text-white/80">
                            Menu belum di-assign untuk role ini.
                        </p>
                    @endforelse

                    <div class="mt-4 border-t border-white/15 pt-4">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button
                                type="submit"
                                class="flex w-full items-center gap-3 rounded-xl px-4 py-3 text-left text-sm font-semibold hover:bg-white/10"
                            >
                                Logout
                            </button>
                        </form>
                    </div>
                </nav>
            </aside>

            <div class="flex-1">
                <header class="border-b border-slate-200 bg-white">
                    <div class="mx-auto flex max-w-6xl items-center justify-between px-6 py-4">
                        <p class="text-sm text-slate-600">
                            Login sebagai <span class="font-semibold text-slate-900">{{ auth()->user()->name ?? '-' }}</span>
                        </p>
                        <a
                            href="{{ route('home') }}"
                            class="rounded-xl bg-[#CE2626] px-4 py-2 text-sm font-semibold text-white hover:bg-[#b81f1f] focus:outline-none focus:ring-4 focus:ring-[#CE2626]/30"
                        >
                            Kembali ke Landing
                        </a>
                    </div>
                </header>

                <main class="mx-auto w-full max-w-6xl px-6 py-10">
                    @if (session('status'))
                        <div class="mb-6 rounded-xl bg-emerald-50 px-4 py-3 text-sm text-emerald-800 ring-1 ring-emerald-200">
                            {{ session('status') }}
                        </div>
                    @endif

                    @yield('content')
                </main>
            </div>
        </div>
    </body>
</html>
