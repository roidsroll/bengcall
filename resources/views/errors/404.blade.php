@extends('layouts.user', ['title' => '404 - Halaman Tidak Ditemukan', 'mainClass' => 'p-0'])

@section('content')
<div class="min-h-[80vh] flex items-center justify-center bg-slate-50 px-6">
    <div class="max-w-md w-full text-center">
        <div class="relative mb-8">
            <div class="absolute inset-0 bg-[#CE2626]/10 blur-3xl rounded-full"></div>
            <h1 class="relative text-9xl font-black text-slate-200 tracking-tighter">404</h1>
            <div class="absolute inset-0 flex items-center justify-center">
                <span class="text-6xl">🧐</span>
            </div>
        </div>
        <h2 class="text-3xl font-bold text-slate-800 mb-3">Waduh, Nyasar Ya?</h2>
        <p class="text-slate-500 mb-8 leading-relaxed">
            Halaman yang kamu cari nggak ada di sini. Mungkin salah ketik URL atau halamannya udah dipindah.
        </p>
        <a href="{{ url('/') }}" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-[#CE2626] px-8 py-3.5 text-sm font-semibold text-white shadow-lg shadow-[#CE2626]/20 transition-all hover:scale-105 hover:bg-[#b81f1f] hover:shadow-xl focus:outline-none focus:ring-4 focus:ring-[#CE2626]/30">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Balik ke Beranda
        </a>
    </div>
</div>
@endsection
