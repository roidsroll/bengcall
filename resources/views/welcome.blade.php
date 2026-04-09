@extends('layouts.user', ['title' => 'bengcall - Bengkel Panggilan', 'mainClass' => 'p-0'])

@section('content')
    {{-- Hero Section --}}
    <section class="relative overflow-hidden bg-white">
        <div
            class="absolute inset-0 -z-20 bg-cover bg-center bg-no-repeat"
            style="background-image: url('{{ asset('adam-skrinnikoff-2TEx9PPF_xU-unsplash.jpg') }}');"
        ></div>
        <div class="absolute inset-0 -z-10 bg-gradient-to-r from-white/95 via-white/85 to-white/70"></div>

        {{-- Decorative circles --}}
        <div class="absolute top-20 right-10 h-64 w-64 rounded-full bg-[#CE2626]/5 blur-3xl"></div>
        <div class="absolute bottom-10 left-10 h-48 w-48 rounded-full bg-[#CE2626]/5 blur-3xl"></div>

        <div class="mx-auto max-w-6xl px-6 py-16 sm:py-24">
            <div class="grid items-center gap-12 lg:grid-cols-2">
                {{-- Left Content --}}
                <div>
                    <p class="inline-flex items-center rounded-full bg-[#CE2626]/10 px-4 py-1.5 text-xs font-semibold text-[#CE2626] backdrop-blur-sm">
                        ⚡ Bengkel panggilan • cepat • transparan
                    </p>
                    <h1 class="mt-5 text-4xl font-bold tracking-tight text-slate-800 sm:text-5xl lg:text-6xl">
                        bengcall, solusi
                        <span class="text-[#CE2626]">bengkel panggilan</span>
                        yang praktis
                    </h1>
                    <p class="mt-4 text-base leading-relaxed text-slate-500">
                        Butuh bantuan di rumah atau di jalan? Kami datang ke lokasi, cek masalah, kasih estimasi,
                        lalu kerjakan dengan rapi. Tanpa ribet.
                    </p>

                    <div class="mt-8 flex flex-wrap items-center gap-4">
                        <a
                            href="{{ route('products') }}"
                            class="inline-flex items-center rounded-2xl bg-[#CE2626] px-6 py-3.5 text-sm font-semibold text-white shadow-lg shadow-[#CE2626]/20 transition-all hover:scale-105 hover:bg-[#b81f1f] hover:shadow-xl focus:outline-none focus:ring-4 focus:ring-[#CE2626]/30"
                        >
                            🚗 Pesan Sekarang
                        </a>
                        <a
                            href="{{ route('login') }}"
                            class="inline-flex items-center rounded-2xl bg-white px-6 py-3.5 text-sm font-semibold text-slate-700 shadow-sm ring-1 ring-slate-200 transition-all hover:bg-slate-50 hover:shadow-md"
                        >
                            👋 Login
                        </a>
                    </div>

                    {{-- Stats --}}
                    <div class="mt-12 grid grid-cols-3 gap-4">
                        <div class="rounded-2xl bg-gradient-to-br from-slate-50 to-white p-4 shadow-sm ring-1 ring-slate-100">
                            <p class="text-xs font-semibold text-slate-400">Respon</p>
                            <p class="mt-1 text-xl font-bold text-slate-800">⚡ Cepat</p>
                        </div>
                        <div class="rounded-2xl bg-gradient-to-br from-slate-50 to-white p-4 shadow-sm ring-1 ring-slate-100">
                            <p class="text-xs font-semibold text-slate-400">Harga</p>
                            <p class="mt-1 text-xl font-bold text-slate-800">💰 Jelas</p>
                        </div>
                        <div class="rounded-2xl bg-gradient-to-br from-slate-50 to-white p-4 shadow-sm ring-1 ring-slate-100">
                            <p class="text-xs font-semibold text-slate-400">Servis</p>
                            <p class="mt-1 text-xl font-bold text-slate-800">✨ Rapi</p>
                        </div>
                    </div>
                </div>

                {{-- Right Content - Layanan Populer --}}
                <div class="relative">
                    <div class="absolute -inset-4 -z-10 rounded-[2.5rem] bg-[#CE2626]/10 blur-2xl"></div>
                    <div class="rounded-[2rem] bg-white/80 p-6 shadow-xl backdrop-blur-sm ring-1 ring-white/50 sm:p-8">
                        <div class="flex items-center gap-2">
                            <span class="text-2xl">🔧</span>
                            <h2 class="text-xl font-bold tracking-tight text-slate-800">Layanan Populer</h2>
                        </div>
                        <p class="mt-1 text-sm text-slate-500">Contoh servis yang paling sering dipesan.</p>

                        <div class="mt-6 space-y-3">
                            {{-- Item 1 --}}
                            <div class="group flex items-start gap-4 rounded-2xl bg-white p-4 shadow-sm transition-all hover:shadow-md hover:ring-1 hover:ring-[#CE2626]/20">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-[#CE2626] to-[#e04e4e] text-white shadow-md">
                                    🔋
                                </div>
                                <div>
                                    <p class="font-semibold text-slate-800">Aki drop / starter</p>
                                    <p class="mt-0.5 text-sm text-slate-500">Cek aki, jumper, ganti aki (jika perlu).</p>
                                </div>
                            </div>

                            {{-- Item 2 --}}
                            <div class="group flex items-start gap-4 rounded-2xl bg-white p-4 shadow-sm transition-all hover:shadow-md hover:ring-1 hover:ring-[#CE2626]/20">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-[#CE2626] to-[#e04e4e] text-white shadow-md">
                                    🛢️
                                </div>
                                <div>
                                    <p class="font-semibold text-slate-800">Servis ringan</p>
                                    <p class="mt-0.5 text-sm text-slate-500">Ganti oli, tune-up, cek rem.</p>
                                </div>
                            </div>

                            {{-- Item 3 --}}
                            <div class="group flex items-start gap-4 rounded-2xl bg-white p-4 shadow-sm transition-all hover:shadow-md hover:ring-1 hover:ring-[#CE2626]/20">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-[#CE2626] to-[#e04e4e] text-white shadow-md">
                                    🚗
                                </div>
                                <div>
                                    <p class="font-semibold text-slate-800">Ban bocor</p>
                                    <p class="mt-0.5 text-sm text-slate-500">Tambal/patch, cek tekanan, rekomendasi.</p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 rounded-2xl bg-gradient-to-r from-[#CE2626] to-[#e04e4e] p-5 text-white shadow-lg">
                            <div class="flex items-center gap-2">
                                <span class="text-xl">📞</span>
                                <p class="font-semibold">Siap bantu sekarang</p>
                            </div>
                            <p class="mt-1 text-sm text-white/90">Daftar akun untuk mulai pesan layanan.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Kenapa bengcall --}}
    <section class="bg-gradient-to-b from-white to-slate-50/50">
        <div class="mx-auto max-w-6xl px-6 py-16">
            <div class="text-center max-w-2xl mx-auto">
                <span class="inline-block rounded-full bg-[#CE2626]/10 px-3 py-1 text-xs font-semibold text-[#CE2626]">✨ Keunggulan</span>
                <h2 class="mt-3 text-3xl font-bold tracking-tight text-slate-800">Kenapa bengcall?</h2>
                <p class="mt-2 text-slate-500">Kami fokus ke pengalaman yang simpel: pesan, estimasi, beres.</p>
            </div>

            <div class="mt-12 grid gap-6 md:grid-cols-3">
                <div class="group rounded-2xl bg-white p-6 text-center shadow-sm transition-all hover:-translate-y-1 hover:shadow-xl ring-1 ring-slate-100">
                    <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-[#CE2626]/10 text-2xl group-hover:bg-[#CE2626] group-hover:text-white transition-all">
                        📊
                    </div>
                    <h3 class="text-lg font-bold text-slate-800">Transparan</h3>
                    <p class="mt-2 text-sm text-slate-500">Estimasi biaya & tindakan jelas sebelum dikerjakan.</p>
                </div>

                <div class="group rounded-2xl bg-white p-6 text-center shadow-sm transition-all hover:-translate-y-1 hover:shadow-xl ring-1 ring-slate-100">
                    <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-[#CE2626]/10 text-2xl group-hover:bg-[#CE2626] group-hover:text-white transition-all">
                        🔧
                    </div>
                    <h3 class="text-lg font-bold text-slate-800">Teknisi berpengalaman</h3>
                    <p class="mt-2 text-sm text-slate-500">Pengerjaan rapi, aman, dan sesuai kebutuhan.</p>
                </div>

                <div class="group rounded-2xl bg-white p-6 text-center shadow-sm transition-all hover:-translate-y-1 hover:shadow-xl ring-1 ring-slate-100">
                    <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-[#CE2626]/10 text-2xl group-hover:bg-[#CE2626] group-hover:text-white transition-all">
                        ⏱️
                    </div>
                    <h3 class="text-lg font-bold text-slate-800">Hemat waktu</h3>
                    <p class="mt-2 text-sm text-slate-500">Tidak perlu antre, kami datang ke lokasi kamu.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Cara Kerja (diperbaiki dengan style soft & friendly) --}}
    <section class="bg-white py-16">
        <div class="mx-auto max-w-6xl px-6">
            <div class="text-center max-w-2xl mx-auto mb-12">
                <span class="inline-block rounded-full bg-[#CE2626]/10 px-3 py-1 text-xs font-semibold text-[#CE2626]">📋 Panduan</span>
                <h2 class="mt-3 text-3xl font-bold tracking-tight text-slate-800">Cara kerja</h2>
                <p class="mt-2 text-slate-500">Tiga langkah mudah sampai kendaraan siap jalan lagi.</p>
            </div>

            <div class="relative">
                {{-- Connecting line --}}
                <div class="absolute left-[50%] top-[60px] hidden h-[calc(100%-120px)] w-0.5 bg-gradient-to-b from-[#CE2626]/20 via-[#CE2626]/40 to-[#CE2626]/20 lg:block"></div>

                <div class="grid gap-8 lg:grid-cols-3">
                    {{-- Step 1 --}}
                    <div class="group relative text-center lg:text-left">
                        <div class="relative mx-auto lg:mx-0 mb-6 flex h-20 w-20 items-center justify-center rounded-3xl bg-gradient-to-br from-[#CE2626] to-[#e04e4e] text-white shadow-lg shadow-[#CE2626]/20 transition-all group-hover:scale-105 group-hover:shadow-xl">
                            <span class="text-3xl font-bold">1</span>
                            <div class="absolute -bottom-2 -right-2 flex h-8 w-8 items-center justify-center rounded-full bg-white shadow-md">
                                <span class="text-lg">📱</span>
                            </div>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800">Pesan layanan</h3>
                        <p class="mt-2 text-slate-500 leading-relaxed">Login/daftar, pilih kebutuhan, isi lokasi. <span class="block text-sm text-[#CE2626]/70 font-medium mt-1">⭐ 2 menit saja</span></p>
                        <div class="mt-4 hidden lg:block h-1 w-12 rounded-full bg-[#CE2626]/20 mx-auto lg:mx-0"></div>
                    </div>

                    {{-- Step 2 --}}
                    <div class="group relative text-center lg:text-left">
                        <div class="relative mx-auto lg:mx-0 mb-6 flex h-20 w-20 items-center justify-center rounded-3xl bg-gradient-to-br from-[#CE2626] to-[#e04e4e] text-white shadow-lg shadow-[#CE2626]/20 transition-all group-hover:scale-105 group-hover:shadow-xl">
                            <span class="text-3xl font-bold">2</span>
                            <div class="absolute -bottom-2 -right-2 flex h-8 w-8 items-center justify-center rounded-full bg-white shadow-md">
                                <span class="text-lg">💰</span>
                            </div>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800">Dapat estimasi</h3>
                        <p class="mt-2 text-slate-500 leading-relaxed">Kami konfirmasi masalah dan estimasi biaya. <span class="block text-sm text-[#CE2626]/70 font-medium mt-1">✅ Tanpa biaya tersembunyi</span></p>
                        <div class="mt-4 hidden lg:block h-1 w-12 rounded-full bg-[#CE2626]/20 mx-auto lg:mx-0"></div>
                    </div>

                    {{-- Step 3 --}}
                    <div class="group relative text-center lg:text-left">
                        <div class="relative mx-auto lg:mx-0 mb-6 flex h-20 w-20 items-center justify-center rounded-3xl bg-gradient-to-br from-[#CE2626] to-[#e04e4e] text-white shadow-lg shadow-[#CE2626]/20 transition-all group-hover:scale-105 group-hover:shadow-xl">
                            <span class="text-3xl font-bold">3</span>
                            <div class="absolute -bottom-2 -right-2 flex h-8 w-8 items-center justify-center rounded-full bg-white shadow-md">
                                <span class="text-lg">🔧</span>
                            </div>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800">Pengerjaan</h3>
                        <p class="mt-2 text-slate-500 leading-relaxed">Teknisi datang, kerjakan, dan cek akhir. <span class="block text-sm text-[#CE2626]/70 font-medium mt-1">🎯 Garansi kepuasan</span></p>
                    </div>
                </div>
            </div>

          
            <div class="mt-12 flex justify-center">
                <div class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-4 py-2 text-sm text-slate-600">
                    <span>💡</span>
                    <span>Butuh bantuan? Tim kami siap membantu 24/7</span>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="bg-gradient-to-br from-slate-50 to-white">
        <div class="mx-auto max-w-6xl px-6 py-16">
            <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-[#CE2626] to-[#e04e4e] p-10 text-white shadow-2xl">
                {{-- Decorative --}}
                <div class="absolute -right-10 -top-10 h-40 w-40 rounded-full bg-white/10 blur-2xl"></div>
                <div class="absolute -bottom-10 -left-10 h-40 w-40 rounded-full bg-white/10 blur-2xl"></div>

                <div class="relative grid gap-8 lg:grid-cols-2 lg:items-center">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-3xl">🚀</span>
                            <h2 class="text-3xl font-bold tracking-tight">Siap panggil bengkel sekarang?</h2>
                        </div>
                        <p class="mt-3 text-white/90 leading-relaxed">
                            Buat akun dan mulai pesan layanan. Cepat, jelas, dan praktis.
                        </p>
                        <div class="mt-4 flex gap-2 text-sm text-white/80">
                            <span>✓</span>
                            <span>Gratis konsultasi</span>
                            <span class="mx-2">•</span>
                            <span>✓</span>
                            <span>Teknisi tersertifikasi</span>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-4 lg:justify-end">
                        <a
                            href="{{ route('products') }}"
                            class="inline-flex items-center gap-2 rounded-2xl bg-white px-6 py-3.5 text-sm font-semibold text-[#CE2626] shadow-lg transition-all hover:scale-105 hover:bg-white/95 hover:shadow-xl"
                        >
                            🚗 Pesan Sekarang
                        </a>
                        <a
                            href="{{ route('login') }}"
                            class="inline-flex items-center gap-2 rounded-2xl bg-white/15 px-6 py-3.5 text-sm font-semibold text-white backdrop-blur-sm transition-all hover:bg-white/25 ring-1 ring-white/30"
                        >
                            👋 Login
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
