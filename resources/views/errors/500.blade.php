@extends('layouts.user', ['title' => '500 - Terjadi Kesalahan', 'mainClass' => 'p-0'])

@section('content')
<div class="min-h-[80vh] flex items-center justify-center bg-slate-50 px-6">
    <div class="max-w-md w-full text-center">
        <div class="relative mb-8">
            <div class="absolute inset-0 bg-[#CE2626]/10 blur-3xl rounded-full"></div>
            <h1 class="relative text-9xl font-black text-slate-200 tracking-tighter">500</h1>
            <div class="absolute inset-0 flex items-center justify-center">
                <span class="text-6xl">🔧</span>
            </div>
        </div>
        <h2 class="text-3xl font-bold text-slate-800 mb-3">Ups, Ada Masalah di Server</h2>
        <p class="text-slate-500 mb-8 leading-relaxed">
            Maaf ya, sistem kami lagi ada sedikit kendala atau error di kode. Tim mekanik kami sedang memperbaikinya!
        </p>
        <a href="{{ url('/') }}" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-[#CE2626] px-8 py-3.5 text-sm font-semibold text-white shadow-lg shadow-[#CE2626]/20 transition-all hover:scale-105 hover:bg-[#b81f1f] hover:shadow-xl focus:outline-none focus:ring-4 focus:ring-[#CE2626]/30">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            Kembali ke Beranda
        </a>
    </div>
</div>
@endsection
