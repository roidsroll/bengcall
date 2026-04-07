@extends('layouts.admin', ['title' => 'Admin Dashboard - bengcall'])

@section('content')
    <div class="rounded-2xl bg-white p-8 shadow-sm ring-1 ring-slate-200">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-semibold tracking-tight text-slate-900">Admin Dashboard</h1>
                <p class="mt-1 text-sm text-slate-600">Selamat datang, {{ auth()->user()->name }}.</p>
            </div>
            <span class="inline-flex items-center rounded-full bg-[#CE2626] px-3 py-1 text-xs font-semibold text-white">
                {{ strtoupper(auth()->user()?->role?->name ?? 'ADMIN') }}
            </span>
        </div>

        <div class="mt-8 grid gap-4 sm:grid-cols-3">
            <div class="rounded-2xl bg-slate-50 p-5 ring-1 ring-slate-200">
                <p class="text-sm font-medium text-slate-700">User</p>
                <p class="mt-2 text-3xl font-semibold text-slate-900">{{ $usersCount ?? '-' }}</p>
            </div>
            <div class="rounded-2xl bg-slate-50 p-5 ring-1 ring-slate-200">
                <p class="text-sm font-medium text-slate-700">Role</p>
                <p class="mt-2 text-3xl font-semibold text-slate-900">{{ $rolesCount ?? '-' }}</p>
            </div>
            <div class="rounded-2xl bg-slate-50 p-5 ring-1 ring-slate-200">
                <p class="text-sm font-medium text-slate-700">Menu</p>
                <p class="mt-2 text-3xl font-semibold text-slate-900">{{ $menusCount ?? '-' }}</p>
            </div>
        </div>

        @php
            $actionMenus = collect($sidebarMenuFlat ?? [])
                ->filter(function ($menu) {
                    $url = trim((string) ($menu->url ?? ''));

                    return $url !== '' && $url !== '#';
                })
                ->unique(fn ($menu) => (string) $menu->url)
                ->values();
        @endphp

        <div class="mt-8 flex flex-wrap gap-3">
            @forelse ($actionMenus as $menu)
                @php
                    $rawUrl = trim((string) $menu->url);
                    $isExternal = str_starts_with($rawUrl, 'http://') || str_starts_with($rawUrl, 'https://');
                    $href = $isExternal ? $rawUrl : url($rawUrl);
                @endphp

                <a
                    href="{{ $href }}"
                    class="inline-flex items-center rounded-xl bg-[#CE2626] px-4 py-2 text-sm font-semibold text-white hover:bg-[#b81f1f] focus:outline-none focus:ring-4 focus:ring-[#CE2626]/30"
                >
                    {{ $menu->name }}
                </a>
            @empty
                <p class="text-sm text-slate-600">Menu untuk role ini belum di-assign.</p>
            @endforelse
        </div>
    </div>
@endsection
