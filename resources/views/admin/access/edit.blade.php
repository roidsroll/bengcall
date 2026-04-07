@extends('layouts.admin', ['title' => 'Akses Menu - bengcall'])

@section('content')
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight text-slate-900">Akses Menu</h1>
            <p class="mt-1 text-sm text-slate-600">
                Role: <span class="font-semibold text-slate-900">{{ $role->name }}</span>
            </p>
        </div>

        <div class="flex items-center gap-3">
            <a
                href="{{ route('admin.access.index') }}"
                class="rounded-xl bg-white px-4 py-2 text-sm font-semibold text-slate-900 ring-1 ring-slate-200 hover:bg-slate-50"
            >
                Kembali
            </a>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.access.update', $role) }}" class="mt-6">
        @csrf
        @method('PUT')

        <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
            <div class="border-b border-slate-200 bg-slate-50 px-6 py-4">
                <p class="text-sm font-semibold text-slate-900">Pilih menu yang boleh diakses</p>
                <p class="mt-1 text-sm text-slate-600">Menu parent bisa dipakai sebagai header (URL boleh kosong).</p>
            </div>

            <div class="px-6 py-5">
                @if ($errors->any())
                    <div class="mb-4 rounded-xl bg-red-50 px-4 py-3 text-sm text-red-800 ring-1 ring-red-200">
                        <ul class="list-disc space-y-1 pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="space-y-2">
                    @forelse ($menus as $item)
                        @php
                            /** @var \App\Models\Menu $menu */
                            $menu = $item['menu'];
                            $depth = (int) $item['depth'];
                            $checked = in_array($menu->id, $selectedMenuIds ?? [], true);
                        @endphp

                        <label class="flex items-start gap-3 rounded-xl px-3 py-2 hover:bg-slate-50">
                            <input
                                type="checkbox"
                                name="menu_ids[]"
                                value="{{ $menu->id }}"
                                @checked($checked)
                                class="mt-1 h-4 w-4 rounded border-slate-300 text-[#CE2626] focus:ring-[#CE2626]/30"
                            />

                            <div class="flex-1" style="padding-left: {{ $depth * 18 }}px">
                                <p class="text-sm font-semibold text-slate-900">
                                    {{ $menu->name }}
                                    @if ($menu->parent_id)
                                        <span class="ml-2 text-xs font-medium text-slate-500">Submenu</span>
                                    @endif
                                </p>
                                <p class="mt-0.5 text-xs text-slate-600">
                                    URL: <span class="font-medium">{{ $menu->url ?? '-' }}</span>
                                    • Order: <span class="font-medium">{{ $menu->order }}</span>
                                </p>
                            </div>
                        </label>
                    @empty
                        <p class="text-sm text-slate-600">Menu belum ada. Silakan buat dulu di Master Menus.</p>
                    @endforelse
                </div>
            </div>

            <div class="border-t border-slate-200 bg-white px-6 py-4">
                <div class="flex items-center justify-end gap-3">
                    <a
                        href="{{ route('admin.access.index') }}"
                        class="rounded-xl bg-white px-4 py-2 text-sm font-semibold text-slate-900 ring-1 ring-slate-200 hover:bg-slate-50"
                    >
                        Batal
                    </a>
                    <button
                        type="submit"
                        class="rounded-xl bg-[#CE2626] px-4 py-2 text-sm font-semibold text-white hover:bg-[#b81f1f] focus:outline-none focus:ring-4 focus:ring-[#CE2626]/30"
                    >
                        Simpan Akses
                    </button>
                </div>
            </div>
        </div>
    </form>
@endsection

