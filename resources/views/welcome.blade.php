@extends('layouts.user', ['title' => 'bengcall - Bengkel Panggilan', 'mainClass' => 'p-0'])

@section('content')
    <section class="relative overflow-hidden bg-white">
        <div class="absolute inset-0 -z-10 bg-gradient-to-b from-[#CE2626]/10 via-white to-white"></div>
        <div class="mx-auto max-w-6xl px-6 py-14 sm:py-20">
            <div class="grid items-center gap-10 lg:grid-cols-2">
                <div>
                    <p class="inline-flex items-center rounded-full bg-[#CE2626]/10 px-3 py-1 text-xs font-semibold text-[#CE2626]">
                        Bengkel panggilan • cepat • transparan
                    </p>
                    <h1 class="mt-4 text-4xl font-semibold tracking-tight text-slate-900 sm:text-5xl">
                        bengcall, solusi bengkel panggilan yang praktis untuk kendaraan kamu
                    </h1>
                    <p class="mt-4 text-base leading-relaxed text-slate-600">
                        Butuh bantuan di rumah atau di jalan? Kami datang ke lokasi, cek masalah, kasih estimasi,
                        lalu kerjakan dengan rapi. Tanpa ribet.
                    </p>

                    <div class="mt-8 flex flex-wrap items-center gap-3">
                        <a
                            href="{{ route('register') }}"
                            class="inline-flex items-center rounded-xl bg-[#CE2626] px-5 py-3 text-sm font-semibold text-white shadow-sm hover:bg-[#b81f1f] focus:outline-none focus:ring-4 focus:ring-[#CE2626]/30"
                        >
                            Pesan Sekarang
                        </a>
                        <a
                            href="{{ route('login') }}"
                            class="inline-flex items-center rounded-xl bg-white px-5 py-3 text-sm font-semibold text-slate-900 ring-1 ring-slate-200 hover:bg-slate-50"
                        >
                            Login
                        </a>
                    </div>

                    <div class="mt-10 grid grid-cols-3 gap-4">
                        <div class="rounded-2xl bg-slate-50 p-4 ring-1 ring-slate-200">
                            <p class="text-xs font-semibold text-slate-500">Respon</p>
                            <p class="mt-1 text-lg font-semibold text-slate-900">Cepat</p>
                        </div>
                        <div class="rounded-2xl bg-slate-50 p-4 ring-1 ring-slate-200">
                            <p class="text-xs font-semibold text-slate-500">Harga</p>
                            <p class="mt-1 text-lg font-semibold text-slate-900">Jelas</p>
                        </div>
                        <div class="rounded-2xl bg-slate-50 p-4 ring-1 ring-slate-200">
                            <p class="text-xs font-semibold text-slate-500">Servis</p>
                            <p class="mt-1 text-lg font-semibold text-slate-900">Rapi</p>
                        </div>
                    </div>
                </div>

                <div class="relative">
                    <div class="absolute -inset-4 -z-10 rounded-[2.25rem] bg-[#CE2626]/10 blur-2xl"></div>
                    <div class="rounded-[2rem] bg-white p-6 shadow-sm ring-1 ring-slate-200 sm:p-8">
                        <h2 class="text-lg font-semibold tracking-tight text-slate-900">Layanan Populer</h2>
                        <p class="mt-1 text-sm text-slate-600">Contoh servis yang paling sering dipesan.</p>

                        <div class="mt-6 space-y-3">
                            <div class="flex items-start gap-3 rounded-2xl bg-slate-50 p-4 ring-1 ring-slate-200">
                                <div class="mt-0.5 h-8 w-8 rounded-xl bg-[#CE2626] text-white grid place-items-center text-sm font-semibold">
                                    A
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-slate-900">Aki drop / starter</p>
                                    <p class="mt-0.5 text-sm text-slate-600">Cek aki, jumper, ganti aki (jika perlu).</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3 rounded-2xl bg-slate-50 p-4 ring-1 ring-slate-200">
                                <div class="mt-0.5 h-8 w-8 rounded-xl bg-[#CE2626] text-white grid place-items-center text-sm font-semibold">
                                    B
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-slate-900">Servis ringan</p>
                                    <p class="mt-0.5 text-sm text-slate-600">Ganti oli, tune-up, cek rem.</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3 rounded-2xl bg-slate-50 p-4 ring-1 ring-slate-200">
                                <div class="mt-0.5 h-8 w-8 rounded-xl bg-[#CE2626] text-white grid place-items-center text-sm font-semibold">
                                    C
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-slate-900">Ban bocor</p>
                                    <p class="mt-0.5 text-sm text-slate-600">Tambal/patch, cek tekanan, rekomendasi.</p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 rounded-2xl bg-[#CE2626] px-5 py-4 text-white">
                            <p class="text-sm font-semibold">Siap bantu sekarang</p>
                            <p class="mt-1 text-sm text-white/90">Daftar akun untuk mulai pesan layanan.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-slate-50">
        <div class="mx-auto max-w-6xl px-6 py-14">
            <div class="max-w-2xl">
                <h2 class="text-2xl font-semibold tracking-tight text-slate-900">Kenapa bengcall?</h2>
                <p class="mt-2 text-slate-600">Kami fokus ke pengalaman yang simpel: pesan, estimasi, beres.</p>
            </div>

            <div class="mt-8 grid gap-4 md:grid-cols-3">
                <div class="rounded-2xl bg-white p-6 ring-1 ring-slate-200">
                    <p class="text-sm font-semibold text-slate-900">Transparan</p>
                    <p class="mt-2 text-sm text-slate-600">Estimasi biaya & tindakan jelas sebelum dikerjakan.</p>
                </div>
                <div class="rounded-2xl bg-white p-6 ring-1 ring-slate-200">
                    <p class="text-sm font-semibold text-slate-900">Teknisi berpengalaman</p>
                    <p class="mt-2 text-sm text-slate-600">Pengerjaan rapi, aman, dan sesuai kebutuhan.</p>
                </div>
                <div class="rounded-2xl bg-white p-6 ring-1 ring-slate-200">
                    <p class="text-sm font-semibold text-slate-900">Hemat waktu</p>
                    <p class="mt-2 text-sm text-slate-600">Tidak perlu antre, kami datang ke lokasi kamu.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-white">
        <div class="mx-auto max-w-6xl px-6 py-14">
            <div class="grid gap-10 lg:grid-cols-2 lg:items-center">
                <div>
                    <h2 class="text-2xl font-semibold tracking-tight text-slate-900">Cara kerja</h2>
                    <p class="mt-2 text-slate-600">Tiga langkah sampai kendaraan siap jalan lagi.</p>
                </div>
                <ol class="grid gap-4">
                    <li class="rounded-2xl bg-slate-50 p-6 ring-1 ring-slate-200">
                        <p class="text-sm font-semibold text-slate-900"><span class="text-[#CE2626]">1.</span> Pesan layanan</p>
                        <p class="mt-2 text-sm text-slate-600">Login/daftar, pilih kebutuhan, isi lokasi.</p>
                    </li>
                    <li class="rounded-2xl bg-slate-50 p-6 ring-1 ring-slate-200">
                        <p class="text-sm font-semibold text-slate-900"><span class="text-[#CE2626]">2.</span> Dapat estimasi</p>
                        <p class="mt-2 text-sm text-slate-600">Kami konfirmasi masalah dan estimasi biaya.</p>
                    </li>
                    <li class="rounded-2xl bg-slate-50 p-6 ring-1 ring-slate-200">
                        <p class="text-sm font-semibold text-slate-900"><span class="text-[#CE2626]">3.</span> Pengerjaan</p>
                        <p class="mt-2 text-sm text-slate-600">Teknisi datang, kerjakan, dan cek akhir.</p>
                    </li>
                </ol>
            </div>
        </div>
    </section>

    <section class="bg-slate-50">
        <div class="mx-auto max-w-6xl px-6 py-14">
            <div class="rounded-3xl bg-[#CE2626] px-8 py-10 text-white sm:px-12">
                <div class="grid gap-6 lg:grid-cols-2 lg:items-center">
                    <div>
                        <h2 class="text-2xl font-semibold tracking-tight">Siap panggil bengkel sekarang?</h2>
                        <p class="mt-2 text-sm text-white/90">
                            Buat akun dan mulai pesan layanan. Cepat, jelas, dan praktis.
                        </p>
                    </div>
                    <div class="flex flex-wrap gap-3 lg:justify-end">
                        <a
                            href="{{ route('register') }}"
                            class="inline-flex items-center rounded-xl bg-white px-5 py-3 text-sm font-semibold text-[#CE2626] hover:bg-white/90"
                        >
                            Register
                        </a>
                        <a
                            href="{{ route('login') }}"
                            class="inline-flex items-center rounded-xl bg-white/10 px-5 py-3 text-sm font-semibold text-white ring-1 ring-white/20 hover:bg-white/15"
                        >
                            Login
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

