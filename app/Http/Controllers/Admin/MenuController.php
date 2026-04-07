<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('q', ''));

        $menus = Menu::query()
            ->with('parent')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($inner) use ($search) {
                    $inner
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('url', 'like', "%{$search}%");
                });
            })
            ->orderByRaw('parent_id is not null') // parent null dulu
            ->orderBy('parent_id')
            ->orderBy('order')
            ->orderBy('id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.menus.index', [
            'menus' => $menus,
            'search' => $search,
        ]);
    }

    public function create()
    {
        return view('admin.menus.create', [
            'parents' => Menu::whereNull('parent_id')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'url' => ['nullable', 'string', 'max:255'],
            'icon' => ['nullable', 'string', 'max:255'],
            'order' => ['required', 'integer', 'min:0'],
            'parent_id' => [
                'nullable',
                'integer',
                Rule::exists('menus', 'id')->whereNull('parent_id'),
            ],
        ]);

        $url = trim((string) ($validated['url'] ?? ''));
        if ($url !== '' && $url !== '#' && ! Menu::isExternalUrl($url)) {
            if (str_contains($url, 'resources/') || str_ends_with($url, '.blade.php')) {
                return back()->withErrors([
                    'url' => 'URL tidak boleh mengarah ke path file view. Pakai path (contoh: /admin/access) atau nama route (contoh: admin.access.index).',
                ])->withInput();
            }
        }

        Menu::create([
            'name' => $validated['name'],
            'url' => $url !== '' ? $url : null,
            'icon' => $validated['icon'] ?? null,
            'order' => (int) $validated['order'],
            'parent_id' => $validated['parent_id'] ? (int) $validated['parent_id'] : null,
        ]);

        return redirect()->route('admin.menus.index')->with('status', 'Menu berhasil dibuat.');
    }

    public function edit(Menu $menu)
    {
        return view('admin.menus.edit', [
            'menu' => $menu->load('parent'),
            'parents' => Menu::whereNull('parent_id')->whereKeyNot($menu->id)->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Menu $menu): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'url' => ['nullable', 'string', 'max:255'],
            'icon' => ['nullable', 'string', 'max:255'],
            'order' => ['required', 'integer', 'min:0'],
            'parent_id' => [
                'nullable',
                'integer',
                Rule::exists('menus', 'id')->whereNull('parent_id'),
            ],
        ]);

        $url = trim((string) ($validated['url'] ?? ''));
        if ($url !== '' && $url !== '#' && ! Menu::isExternalUrl($url)) {
            if (str_contains($url, 'resources/') || str_ends_with($url, '.blade.php')) {
                return back()->withErrors([
                    'url' => 'URL tidak boleh mengarah ke path file view. Pakai path (contoh: /admin/access) atau nama route (contoh: admin.access.index).',
                ])->withInput();
            }
        }

        if (! empty($validated['parent_id']) && (int) $validated['parent_id'] === (int) $menu->id) {
            return back()->withErrors(['parent_id' => 'Parent tidak boleh dirinya sendiri.'])->withInput();
        }

        $menu->update([
            'name' => $validated['name'],
            'url' => $url !== '' ? $url : null,
            'icon' => $validated['icon'] ?? null,
            'order' => (int) $validated['order'],
            'parent_id' => $validated['parent_id'] ? (int) $validated['parent_id'] : null,
        ]);

        return redirect()->route('admin.menus.index')->with('status', 'Menu berhasil diupdate.');
    }

    public function destroy(Menu $menu): RedirectResponse
    {
        $menu->delete();

        return redirect()->route('admin.menus.index')->with('status', 'Menu berhasil dihapus.');
    }
}
