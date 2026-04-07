<?php

namespace App\View\Composers;

use App\Models\Menu;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdminSidebarComposer
{
    public function compose(View $view): void
    {
        $user = Auth::user();
        $role = $user?->role;

        if (! $role) {
            $view->with('sidebarMenus', []);
            $view->with('sidebarMenuFlat', []);

            return;
        }

        $assignedMenus = $role->menus()
            ->select(['menus.id', 'menus.parent_id', 'menus.name', 'menus.url', 'menus.icon', 'menus.order'])
            ->get();

        if ($assignedMenus->isEmpty()) {
            $view->with('sidebarMenus', []);
            $view->with('sidebarMenuFlat', []);

            return;
        }

        $menuIds = $this->collectMenuIdsIncludingParents($assignedMenus);

        $menus = Menu::query()
            ->whereIn('id', $menuIds)
            ->orderByRaw('parent_id is not null')
            ->orderBy('parent_id')
            ->orderBy('order')
            ->orderBy('name')
            ->orderBy('id')
            ->get(['id', 'parent_id', 'name', 'url', 'icon', 'order']);

        $tree = $this->buildTree($menus);
        $tree = $this->markActive($tree, (string) request()->path());

        $view->with('sidebarMenus', $tree);
        $view->with('sidebarMenuFlat', $this->flattenTree($tree));
    }

    /**
     * @param  \Illuminate\Support\Collection<int, Menu>  $assignedMenus
     * @return array<int, int>
     */
    private function collectMenuIdsIncludingParents(Collection $assignedMenus): array
    {
        $menuIds = $assignedMenus->pluck('id')->map(fn ($id) => (int) $id)->unique()->values()->all();
        $knownIds = array_fill_keys($menuIds, true);

        $pendingParentIds = $assignedMenus
            ->pluck('parent_id')
            ->filter(fn ($id) => ! empty($id))
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();

        while ($pendingParentIds->isNotEmpty()) {
            $parents = Menu::query()
                ->whereIn('id', $pendingParentIds->all())
                ->get(['id', 'parent_id']);

            $pendingParentIds = collect();

            foreach ($parents as $parent) {
                $parentId = (int) $parent->id;

                if (! isset($knownIds[$parentId])) {
                    $knownIds[$parentId] = true;
                    $menuIds[] = $parentId;
                }

                if (! empty($parent->parent_id)) {
                    $nextParentId = (int) $parent->parent_id;

                    if (! isset($knownIds[$nextParentId])) {
                        $pendingParentIds->push($nextParentId);
                    }
                }
            }

            $pendingParentIds = $pendingParentIds->unique()->values();
        }

        return $menuIds;
    }

    /**
     * @param  \Illuminate\Support\Collection<int, Menu>  $menus
     * @return array<int, array{menu: Menu, children: array, is_active: bool, is_open: bool}>
     */
    private function buildTree(Collection $menus): array
    {
        $childrenByParent = $menus->groupBy(fn (Menu $menu) => $menu->parent_id ?? 0);

        $build = function (int $parentId) use (&$build, $childrenByParent): array {
            $result = [];

            foreach (($childrenByParent[$parentId] ?? collect()) as $menu) {
                $result[] = [
                    'menu' => $menu,
                    'children' => $build((int) $menu->id),
                    'is_active' => false,
                    'is_open' => false,
                ];
            }

            return $result;
        };

        return $build(0);
    }

    /**
     * @param  array<int, array{menu: Menu, children: array, is_active: bool, is_open: bool}>  $nodes
     * @return array<int, array{menu: Menu, children: array, is_active: bool, is_open: bool}>
     */
    private function markActive(array $nodes, string $currentPath): array
    {
        foreach ($nodes as $index => $node) {
            $menu = $node['menu'];
            $children = $this->markActive($node['children'], $currentPath);

            $isActive = $this->isMenuActive($menu->url, $currentPath);
            $isOpen = $isActive || collect($children)->contains(fn ($child) => (bool) ($child['is_open'] ?? false) || (bool) ($child['is_active'] ?? false));

            $nodes[$index]['children'] = $children;
            $nodes[$index]['is_active'] = $isActive;
            $nodes[$index]['is_open'] = $isOpen;
        }

        return $nodes;
    }

    private function isMenuActive(?string $url, string $currentPath): bool
    {
        $url = trim((string) $url);

        if ($url === '' || $url === '#') {
            return false;
        }

        if (Menu::isExternalUrl($url)) {
            return false;
        }

        $normalized = Menu::normalizeInternalPath($url);

        return $normalized !== ''
            && ($currentPath === $normalized || str_starts_with($currentPath, $normalized.'/'));
    }

    /**
     * @param  array<int, array{menu: Menu, children: array, is_active: bool, is_open: bool}>  $nodes
     * @return array<int, Menu>
     */
    private function flattenTree(array $nodes): array
    {
        $result = [];

        foreach ($nodes as $node) {
            $result[] = $node['menu'];
            $result = array_merge($result, $this->flattenTree($node['children']));
        }

        return $result;
    }
}
