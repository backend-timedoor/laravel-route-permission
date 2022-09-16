# Laravel Route Permission

Extended from [spatie/laravel-permission](https://spatie.be/docs/laravel-permission/v5/introduction) version 5, this package help developer authorize user permission based on URI and request method.

## Installation

Use the package manager [composer](https://getcomposer.org/) to install this package.

```bash
composer require timedoor/laravel-route-permission
```

Publish the package in your project with:

```bash
php artisan routepermission:install
```

Then run the database migration.

```bash
php artisan migrate
```

## Usage

You can use provided route middleware alias from this package named `route-permission` that can be configured from `config/permission.php`, for example:

```php
Route::middleware(['route-permission'])->group(function () {
    //
});
```

Or if you using a guard you can use it like this, for example an `api` guard.

```php
Route::middleware(['route-permission:api'])->group(function () {
    //
});
```

Your user must be logged in if you use this middleware, this middleware will automatically match logged-in user permission with the current route URI.

For creating permission for REST API you can use provided permission model by this package, for example:

```php
use Timedoor\RoutePermission\Models\Permission;

Permission::createForRoute(['uri' => 'api/user']);

Permission::createForRoute(['uri' => 'api/user/{id}', 'POST']);

Permission::createForRoute(['uri' => 'api/user', 'guard_name' => 'api']);

Permission::createForRoute(['uri' => 'api/user/*']);
```

When saving the permission this package will trim all whitespace and `/` character from provided `uri`.

You can use `*` as a wildcard character for route permissions.

Make sure you include the traits from `Timedoor\RoutePermission` for role and permission models to use the extended features for the route.

For the `uri` value use string provided from one of the below codes for accurate reference.

```php
\Route::current()->uri(); // Return current route URI.

collect(\Route::getRoutes())->map(function ($route) { return $route->uri(); }); // Listing all registered route URI.
```

In the database, the permission name will be stored as `route>>api/user/>>*` (if the second parameter isn't provided it will be stored as a wildcard `*`) or `route>>api/user/{parameter}/>>POST` (all route parameters will be converted to `parameter` or you can change the behaviour from `config/permission.php`)

You can access it with default `spatie/laravel-permission` utility like:

```php
$user->can('route>>api/user/{parameter}/>>POST');
```

Or using provided helper function to generate formatted permission name.

```php
$user->can(getRoutePermissionName('api/user/{id}', 'POST'));
```

For manipulating route permission here's the list of other extended methods:

```php
use Timedoor\RoutePermission\Models\Permission;

Permission::createForRoute(['uri' => 'api/user']);

Permission::firstOrCreateForRoute(['uri' => 'api/user']);

Permission::findByNameForRoute('api/user/{id}', 'POST');

Permission::findOrCreateForRoute('api/user/{id}', 'POST');

// Scope a query to only include route permissions.
Permission::route()->get();

// Scope a query to not include route permissions.
Permission::withoutRoute()->get();

// You can call below methods from role instance too.
$user->givePermissionForRoute('api/user/{id}', 'POST');

$user->revokePermissionForRoute('api/user/{id}', 'POST');

$user->syncForRoute([
    ['uri' => 'api/user'],
    ['uri' => 'api/user/{id}', 'method' => 'POST'],
]);

$user->hasPermissionForRoute('api/user/{id}', 'POST');
```

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

## License
[MIT](https://choosealicense.com/licenses/mit/)# Laravel Route Permission

Extended from [spatie/laravel-permission](https://spatie.be/docs/laravel-permission/v5/introduction) version 5, this package help developer authorize user permission based on URI and request method.

## Installation

Use the package manager [composer](https://getcomposer.org/) to install this package.

```bash
composer require timedoor/laravel-route-permission
```

Publish the package in your project with:

```bash
php artisan routepermission:install
```

Then run the database migration.

```bash
php artisan migrate
```

## Usage

You can use provided route middleware alias from this package named `route-permission` that can be configured from `config/permission.php`, for example:

```php
Route::middleware(['route-permission'])->group(function () {
    //
});
```

Or if you using a guard you can use it like this, for example an `api` guard.

```php
Route::middleware(['route-permission:api'])->group(function () {
    //
});
```

Your user must be logged in if you use this middleware, this middleware will automatically match logged-in user permission with the current route URI.

For creating permission for REST API you can use provided permission model by this package, for example:

```php
use Timedoor\RoutePermission\Models\Permission;

Permission::createForRoute(['uri' => 'api/user']);

Permission::createForRoute(['uri' => 'api/user/{id}', 'POST']);

Permission::createForRoute(['uri' => 'api/user', 'guard_name' => 'api']);

Permission::createForRoute(['uri' => 'api/user/*']);
```

When saving the permission this package will trim all whitespace and `/` character from provided `uri`.

You can use `*` as a wildcard character for route permissions.

Make sure you include the traits from `Timedoor\RoutePermission` for role and permission models to use the extended features for the route.

For the `uri` value use string provided from one of the below codes for accurate reference.

```php
\Route::current()->uri(); // Return current route URI.

collect(\Route::getRoutes())->map(function ($route) { return $route->uri(); }); // Listing all registered route URI.
```

In the database, the permission name will be stored as `route>>api/user/>>*` (if the second parameter isn't provided it will be stored as a wildcard `*`) or `route>>api/user/{parameter}/>>POST` (all route parameters will be converted to `parameter` or you can change the behaviour from `config/permission.php`)

You can access it with default `spatie/laravel-permission` utility like:

```php
$user->can('route>>api/user/{parameter}/>>POST');
```

Or using provided helper function to generate formatted permission name.

```php
$user->can(getRoutePermissionName('api/user/{id}', 'POST'));
```

For manipulating route permission here's the list of other extended methods:

```php
use Timedoor\RoutePermission\Models\Permission;

Permission::createForRoute(['uri' => 'api/user']);

Permission::firstOrCreateForRoute(['uri' => 'api/user']);

Permission::findByNameForRoute('api/user/{id}', 'POST');

Permission::findOrCreateForRoute('api/user/{id}', 'POST');

// Scope a query to only include route permissions.
Permission::route()->get();

// Scope a query to not include route permissions.
Permission::withoutRoute()->get();

// You can call below methods from role instance too.
$user->givePermissionForRoute('api/user/{id}', 'POST');

$user->revokePermissionForRoute('api/user/{id}', 'POST');

$user->syncForRoute([
    ['uri' => 'api/user'],
    ['uri' => 'api/user/{id}', 'method' => 'POST'],
]);

$user->hasPermissionForRoute('api/user/{id}', 'POST');
```

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

## License
[MIT](https://choosealicense.com/licenses/mit/)