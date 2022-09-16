<?php

namespace Timedoor\RoutePermission\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class InstallRoutePermission extends Command
{
    protected $signature = 'routepermission:install';

    protected $description = 'Install the Laravel Route Permission';

    public function handle()
    {
        $this->info('Installing spatie/laravel-permission...');

        $this->publishSpatiePackage();

        $this->info('Installing timedoor/laravel-route-permission...');

        $this->publishRoutePermission();

        $this->info('Installed Laravel Route Permission');
    }

    private function publishSpatiePackage()
    {
        $params = [
            '--provider' => "Spatie\Permission\PermissionServiceProvider",
        ];

       $this->call('vendor:publish', $params);
    }

    private function publishRoutePermission()
    {
        $params = [
            '--provider' => "Timedoor\RoutePermission\RoutePermissionServiceProvider",
            '--force' => "true",
        ];

       $this->call('vendor:publish', $params);
    }
}