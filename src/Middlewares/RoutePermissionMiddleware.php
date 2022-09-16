<?php

namespace Timedoor\RoutePermission\Middlewares;

use Closure;
use Spatie\Permission\Exceptions\UnauthorizedException;

class RoutePermissionMiddleware
{
    public function handle($request, Closure $next, $guard = null)
    {
        $authGuard = app('auth')->guard($guard);

        if ($authGuard->guest()) {
            throw UnauthorizedException::notLoggedIn();
        }
        
        $uri = $request->route()->uri;
        $method = $request->method();

        if ($authGuard->user()->can(getRoutePermissionName($uri, $method))) {
            return $next($request);
        }

        $permissions[] = "{$method} {$uri}";

        throw UnauthorizedException::forPermissions($permissions);
    }
}