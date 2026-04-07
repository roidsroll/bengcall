<?php

namespace App\Http\Middleware;

use App\Models\Menu;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMenu
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! $user->role) {
            abort(403);
        }

        $currentPath = (string) $request->path();

        $normalizedPaths = $user->role->menus()
            ->whereNotNull('url')
            ->pluck('url')
            ->map(fn ($url) => Menu::normalizeInternalPath($url))
            ->filter(fn ($normalized) => $normalized !== '')
            ->values();

        foreach ($normalizedPaths as $normalized) {
            if ($currentPath === $normalized || str_starts_with($currentPath, $normalized.'/')) {
                return $next($request);
            }
        }

        abort(403);
    }
}
