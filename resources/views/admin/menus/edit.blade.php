@extends('layouts.admin', ['title' => 'Edit Menu - bengcall'])

@section('content')
    <div class="mx-auto max-w-2xl">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold tracking-tight text-slate-900">Edit Menu</h1>
                <p class="mt-1 text-sm text-slate-600">Update data menu.</p>
            </div>
            <a
                href="{{ route('admin.menus.index') }}"
                class="rounded-xl bg-white px-4 py-2 text-sm font-semibold text-slate-900 ring-1 ring-slate-200 hover:bg-slate-50"
            >
                Kembali
            </a>
        </div>

        @if ($errors->any())
            <div class="mt-4 rounded-xl bg-red-50 px-4 py-3 text-sm text-red-800 ring-1 ring-red-200">
                <ul class="list-disc space-y-1 pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.menus.update', $menu) }}" class="mt-6 space-y-4">
            @csrf
            @method('PUT')

            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <label class="text-sm font-medium text-slate-700" for="name">Nama</label>
                        <input
                            id="name"
                            name="name"
                            type="text"
                            value="{{ old('name', $menu->name) }}"
                            required
                            class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-slate-900 shadow-sm outline-none focus:border-[#CE2626] focus:ring-4 focus:ring-[#CE2626]/20"
                        />
                    </div>

                    <div class="sm:col-span-2">
                        <label class="text-sm font-medium text-slate-700" for="url">URL</label>
                        <input
                            id="url"
                            name="url"
                            type="text"
                            value="{{ old('url', $menu->url) }}"
                            class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-slate-900 shadow-sm outline-none focus:border-[#CE2626] focus:ring-4 focus:ring-[#CE2626]/20"
                        />
                        <p class="mt-1 text-xs text-slate-500">Opsional. Bisa diisi path (contoh: <span class="font-mono">/admin/access</span>) atau nama route (contoh: <span class="font-mono">admin.access.index</span>).</p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-slate-700" for="parent_id">Parent (opsional)</label>
                        <select
                            id="parent_id"
                            name="parent_id"
                            class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-slate-900 shadow-sm outline-none focus:border-[#CE2626] focus:ring-4 focus:ring-[#CE2626]/20"
                        >
                            <option value="">- Menu Utama -</option>
                            @foreach ($parents as $parent)
                                <option
                                    value="{{ $parent->id }}"
                                    @selected((string) old('parent_id', (string) $menu->parent_id) === (string) $parent->id)
                                >
                                    {{ $parent->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-slate-700" for="order">Order</label>
                        <input
                            id="order"
                            name="order"
                            type="number"
                            min="0"
                            value="{{ old('order', $menu->order) }}"
                            required
                            class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-slate-900 shadow-sm outline-none focus:border-[#CE2626] focus:ring-4 focus:ring-[#CE2626]/20"
                        />
                    </div>

                    <div class="sm:col-span-2">
                        <label class="text-sm font-medium text-slate-700" for="icon">Icon (opsional)</label>
                        <input
                            id="icon"
                            name="icon"
                            type="text"
                            value="{{ old('icon', $menu->icon) }}"
                            class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-slate-900 shadow-sm outline-none focus:border-[#CE2626] focus:ring-4 focus:ring-[#CE2626]/20"
                        />
                        <p class="mt-1 text-xs text-slate-500">Isi class Font Awesome. Contoh: <span class="font-mono">fa-solid fa-house</span>.</p>
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end gap-3">
                    <a
                        href="{{ route('admin.menus.index') }}"
                        class="rounded-xl bg-white px-4 py-2 text-sm font-semibold text-slate-900 ring-1 ring-slate-200 hover:bg-slate-50"
                    >
                        Batal
                    </a>
                    <button
                        type="submit"
                        class="rounded-xl bg-[#CE2626] px-4 py-2 text-sm font-semibold text-white hover:bg-[#b81f1f] focus:outline-none focus:ring-4 focus:ring-[#CE2626]/30"
                    >
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection
