<?php

namespace Timedoor\RoutePermission\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Timedoor\RoutePermission\Traits\HasRoles;
use Spatie\Permission\Models\Permission as SpatiePermission;
use Spatie\Permission\Contracts\Permission as PermissionContract;
use Spatie\Permission\PermissionRegistrar;
use Timedoor\RoutePermission\RoutePermission;

class Permission extends SpatiePermission
{
    use HasRoles;

    protected $appends = ['uri', 'method'];
    
    public static function createForRoute(array $attributes = [])
    {
        $attributes = self::setRouteAttributes($attributes);

        return self::create($attributes);
    }

    public static function firstOrCreateForRoute(array $attributes = [])
    {
        $attributes = self::setRouteAttributes($attributes);

        return self::firstOrCreate($attributes);
    }
    
    /**
     * Find a permission by its name for route (and optionally guardName).
     *
     * @param string $uri
     * @param string $method
     * @param string|null $guardName
     *
     * @throws \Spatie\Permission\Exceptions\PermissionDoesNotExist
     *
     * @return \Spatie\Permission\Contracts\Permission
     */
    public static function findByNameForRoute(string $uri, string $method = "*", $guardName = null): PermissionContract
    {
        $name = getRoutePermissionName($uri, $method);

        return self::findByName($name, $guardName);
    }

    /**
     * Find or create permission by its name (and optionally guardName).
     *
     * @param string $uri
     * @param string|bool $method
     * @param string|null $guardName
     *
     * @return \Spatie\Permission\Contracts\Permission
     */
    public static function findOrCreateForRoute(string $uri, $method = true, $guardName = null): PermissionContract
    {
        $routePermission = new RoutePermission($uri, $method);
        
        $name = getRoutePermissionName($routePermission->getUri(), $routePermission->getMethod());

        return self::findByName($name, $guardName);
    }

    protected static function setRouteAttributes(array $attributes)
    {
        $attributes['method'] = isset($attributes['method']) ? $attributes['method'] : true;
        
        $routePermission = new RoutePermission($attributes['uri'], $attributes['method']);

        $attributes['name'] = getRoutePermissionName($routePermission->getUri(), $routePermission->getMethod());
        unset($attributes['uri']);
        unset($attributes['method']);

        return $attributes;
    }

    /**
     * Scope a query to only include route permissions.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return void
     */
    public function scopeRoute($query)
    {
        $prefix = getRoutePermissionPrefix(true);

        $query->where('name', 'like', $prefix.'%');
    }

    /**
     * Scope a query to not include route permissions.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return void
     */
    public function scopeWithoutRoute($query)
    {
        $prefix = getRoutePermissionPrefix(true);

        $query->where('name', 'not like', $prefix.'%');
    }

    /**
     * Get the route permission's uri.
     *
     * @return string|null
     */
    public function getUriAttribute()
    {
        $array = routePermissionNameToArray($this->name);

        return $array ? $array['uri'] : null;
    }

    /**
     * Get the route permission's method.
     *
     * @return string|null
     */
    public function getMethodAttribute()
    {
        $array = routePermissionNameToArray($this->name);
        
        return $array ? $array['method'] : null;
    }

    /**
     * A permission can be applied to roles.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            config('permission.models.role'),
            config('permission.table_names.role_has_permissions'),
            PermissionRegistrar::$pivotPermission,
            PermissionRegistrar::$pivotRole
        );
    }

    /**
     * A permission belongs to some users of the model associated with its guard.
     */
    public function users(): BelongsToMany
    {
        return $this->morphedByMany(
            getModelForGuard($this->attributes['guard_name']),
            'model',
            config('permission.table_names.model_has_permissions'),
            PermissionRegistrar::$pivotPermission,
            config('permission.column_names.model_morph_key')
        );
    }
}
