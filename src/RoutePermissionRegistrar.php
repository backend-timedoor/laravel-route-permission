<?php

namespace Timedoor\RoutePermission;

use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Access\Gate;
use Spatie\Permission\PermissionRegistrar;

class RoutePermissionRegistrar extends PermissionRegistrar
{
    /**
     * Register the route permission check method on the gate.
     * We resolve the Gate fresh here, for benefit of long-running instances.
     *
     * @return bool
     */
    public function registerPermissions(): bool
    {
        app(Gate::class)->before(function (Authorizable $user, string $ability) {
            if (method_exists($user, 'checkPermissionForRoute')) {
                return $user->checkPermissionForRoute($ability, false) ?: null;
            }
        });

        return true;
    }
}
