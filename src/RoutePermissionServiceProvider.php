<?php

namespace Timedoor\RoutePermission;

use Timedoor\RoutePermission\Console\InstallRoutePermission;
use Timedoor\RoutePermission\Middlewares\RoutePermissionMiddleware;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;

class RoutePermissionServiceProvider extends ServiceProvider
{
    public function boot(RoutePermissionRegistrar $permissionLoader)
    {
        $this->offerPublishing();

        $this->registerCommands();

        $this->registerMiddlewares();

        if ($this->app->config['permission.register_permission_check_method']) {
            $permissionLoader->registerPermissions();
        }

        $this->app->singleton(RoutePermissionRegistrar::class, function ($app) use ($permissionLoader) {
            return $permissionLoader;
        });
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/permission.php',
            'permission'
        );
    }

    protected function offerPublishing()
    {
        if (! function_exists('config_path')) {
            // function not available and 'publish' not relevant in Lumen
            return;
        }

        $this->publishes([
            __DIR__.'/../config/permission.php' => config_path('permission.php'),
        ], 'config');
    }

    protected function registerCommands()
    {
        $this->commands([
            InstallRoutePermission::class,
        ]);
    }

    protected function registerMiddlewares()
    {
        $router = $this->app->make(Router::class);
        $router->aliasMiddleware(config('permission.route.middleware'), RoutePermissionMiddleware::class);
    }
}
