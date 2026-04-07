
@php
    /** @var array{menu:\App\Models\Menu, children:array, is_active?:bool, is_open?:bool} $node */
    /** @var int $level */
    /** @var string|null $activeMenuUrlNormalized */


    $menu = $node['menu'];
    $children = $node['children'] ?? [];

    $rawUrl = trim((string) ($menu->url ?? ''));
    $hasLink = $rawUrl !== '' && $rawUrl !== '#';
    $isExternal = \App\Models\Menu::isExternalUrl($rawUrl);
    $href = $hasLink ? \App\Models\Menu::resolveHref($rawUrl) : null;

    $rawIcon = trim((string) ($menu->icon ?? ''));
    $looksLikeFontAwesome = $rawIcon !== '' && (
        str_contains($rawIcon, 'fa-') ||
        str_contains($rawIcon, ' fa') ||
        str_starts_with($rawIcon, 'fa ') ||
        str_starts_with($rawIcon, 'fas') ||
        str_starts_with($rawIcon, 'far') ||
        str_starts_with($rawIcon, 'fab')
    );
    $hasFaStyle = $looksLikeFontAwesome && (
        str_contains($rawIcon, 'fa-solid') ||
        str_contains($rawIcon, 'fa-regular') ||
        str_contains($rawIcon, 'fa-brands') ||
        str_contains($rawIcon, 'fa-light') ||
        str_contains($rawIcon, 'fa-thin') ||
        str_contains($rawIcon, 'fa-duotone') ||
        str_starts_with($rawIcon, 'fas') ||
        str_starts_with($rawIcon, 'far') ||
        str_starts_with($rawIcon, 'fab')
    );
    $iconClass = $looksLikeFontAwesome ? trim(($hasFaStyle ? '' : 'fa-solid ') . $rawIcon) : null;

    $normalizedUrl = (! $isExternal && $rawUrl !== '' && $rawUrl !== '#') ? \App\Models\Menu::normalizeInternalPath($rawUrl) : '';
    $isActive = $activeMenuUrlNormalized
        ? ($normalizedUrl !== '' && $normalizedUrl === $activeMenuUrlNormalized)
        : (bool) ($node['is_active'] ?? false);
    $isOpen = (bool) ($node['is_open'] ?? false);
    $itemBase = $level === 0
        ? 'flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold'
        : 'flex items-center gap-3 rounded-xl px-4 py-2 text-sm font-medium text-white/95';

    $itemState = $isActive ? 'bg-white/15' : 'hover:bg-white/10';
@endphp

@if (count($children) > 0)
    <details class="group space-y-1 sidebar-dropdown" @if($isOpen) open @endif>
        <summary class="{{ $itemBase }} {{ $isOpen ? 'bg-white/10' : 'hover:bg-white/10' }} list-none cursor-pointer select-none">
            @if ($iconClass)
                <i class="{{ $iconClass }} fa-fw shrink-0 opacity-90" aria-hidden="true"></i>
            @endif
            <span class="flex-1">{{ $menu->name }}</span>
            <svg
                class="h-4 w-4 shrink-0 opacity-90 transition-transform duration-200 ease-out group-open:rotate-90"
                viewBox="0 0 20 20"
                fill="currentColor"
                aria-hidden="true"
            >
                <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L10.94 10 7.23 6.29a.75.75 0 111.06-1.06l4.24 4.24a.75.75 0 010 1.06l-4.24 4.24a.75.75 0 01-1.08-.02z" clip-rule="evenodd" />
            </svg>
        </summary>

        <div class="sidebar-dropdown-panel ml-3 space-y-1 border-l border-white/15 pl-3">
            @foreach ($children as $childNode)
                @include('layouts.partials.admin-sidebar-item', [
                    'node' => $childNode,
                    'level' => $level + 1,
                    'activeMenuUrlNormalized' => $activeMenuUrlNormalized,
                ])
            @endforeach
        </div>
    </details>
@else
    @if ($href)
        <a href="{{ $href }}" class="{{ $itemBase }} {{ $itemState }}">
            @if ($iconClass)
                <i class="{{ $iconClass }} fa-fw shrink-0 opacity-90" aria-hidden="true"></i>
            @endif
            <span class="flex-1">{{ $menu->name }}</span>
        </a>
    @else
        <div class="{{ $itemBase }} {{ $itemState }}">
            @if ($iconClass)
                <i class="{{ $iconClass }} fa-fw shrink-0 opacity-90" aria-hidden="true"></i>
            @endif
            <span class="flex-1">{{ $menu->name }}</span>
        </div>
    @endif
@endif
