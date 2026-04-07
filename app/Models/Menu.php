<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Route;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'name',
        'url',
        'icon',
        'order',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'menu_roles')->withTimestamps();
    }

    public static function isExternalUrl(?string $url): bool
    {
        $raw = trim((string) $url);

        return str_starts_with($raw, 'http://') || str_starts_with($raw, 'https://');
    }

    public static function looksLikeRouteName(?string $url): bool
    {
        $raw = trim((string) $url);

        return $raw !== '' && ! str_contains($raw, '/') && str_contains($raw, '.');
    }

    /**
     * Resolve stored menu url into an internal path (e.g. '/admin/access').
     * Supports either a path ('/admin/access') or a route name ('admin.access.index').
     */
    public static function resolveInternalPath(?string $url): string
    {
        $raw = trim((string) $url);

        if ($raw === '' || $raw === '#') {
            return '';
        }

        if (self::looksLikeRouteName($raw) && Route::has($raw)) {
            return route($raw, [], false);
        }

        return $raw;
    }

    /**
     * Normalize stored menu url to compare with Request::path() (no leading slash).
     */
    public static function normalizeInternalPath(?string $url): string
    {
        if (self::isExternalUrl($url)) {
            return '';
        }

        $path = self::resolveInternalPath($url);

        return $path !== '' ? trim($path, '/') : '';
    }

    public static function resolveHref(?string $url): ?string
    {
        $raw = trim((string) $url);

        if ($raw === '' || $raw === '#') {
            return null;
        }

        if (self::isExternalUrl($raw)) {
            return $raw;
        }

        $internal = self::resolveInternalPath($raw);

        return $internal !== '' ? url($internal) : null;
    }
}
