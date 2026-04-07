@extends('layouts.user', ['title' => 'User Page - bengcall', 'mainClass' => 'mx-auto w-full max-w-6xl px-6 py-10'])

@section('content')
    <div class="rounded-2xl bg-white p-8 shadow-sm ring-1 ring-slate-200">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-semibold tracking-tight text-slate-900">User Page</h1>
                <p class="mt-1 text-sm text-slate-600">Halo, {{ auth()->user()->name }}.</p>
            </div>
            <span class="inline-flex items-center rounded-full bg-[#CE2626] px-3 py-1 text-xs font-semibold text-white">
                USER
            </span>
        </div>

        <div class="mt-6 grid gap-4 sm:grid-cols-2">
            <div class="rounded-2xl bg-slate-50 p-5 ring-1 ring-slate-200">
                <p class="text-sm font-medium text-slate-700">Email</p>
                <p class="mt-2 font-semibold text-slate-900">{{ auth()->user()->email }}</p>
            </div>
            <div class="rounded-2xl bg-slate-50 p-5 ring-1 ring-slate-200">
                <p class="text-sm font-medium text-slate-700">Gender</p>
                <p class="mt-2 font-semibold text-slate-900">{{ auth()->user()->gender }}</p>
            </div>
        </div>
    </div>
@endsection
