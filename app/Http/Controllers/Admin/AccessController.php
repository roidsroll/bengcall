<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AccessController extends Controller
{
    public function index()
    {
        return view('admin.access.index', [
            'roles' => Role::withCount('users')->orderBy('name')->get(),
        ]);
    }

    public function edit(Role $role)
    {
        $menus = Menu::query()
            ->orderByRaw('parent_id is not null')
            ->orderBy('parent_id')
            ->orderBy('order')
            ->orderBy('id')
            ->get();

        $menuChildren = $menus->groupBy(fn (Menu $menu) => $menu->parent_id ?? 0);

        $flatMenus = $this->flattenMenus($menuChildren, 0);

        return view('admin.access.edit', [
            'role' => $role->load('menus'),
            'menus' => $flatMenus,
            'selectedMenuIds' => $role->menus->pluck('id')->all(),
        ]);
    }

    public function update(Request $request, Role $role): RedirectResponse
    {
        $validated = $request->validate([
            'menu_ids' => ['nullable', 'array'],
            'menu_ids.*' => ['integer', 'exists:menus,id'],
        ]);

        $menuIds = array_values(array_unique(array_map('intval', $validated['menu_ids'] ?? [])));

        $role->menus()->sync($menuIds);

        return redirect()->route('admin.access.edit', $role)->with('status', 'Akses menu berhasil disimpan.');
    }

    /**
     * @param  \Illuminate\Support\Collection<int, \Illuminate\Support\Collection<int, Menu>>  $menuChildren
     * @return array<int, array{menu: Menu, depth: int}>
     */
    private function flattenMenus($menuChildren, int $parentId, int $depth = 0): array
    {
        $result = [];

        foreach (($menuChildren[$parentId] ?? collect()) as $menu) {
            $result[] = ['menu' => $menu, 'depth' => $depth];
            $result = array_merge($result, $this->flattenMenus($menuChildren, (int) $menu->id, $depth + 1));
        }

        return $result;
    }
}

