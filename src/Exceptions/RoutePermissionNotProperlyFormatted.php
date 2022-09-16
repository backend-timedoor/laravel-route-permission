<?php

namespace Timedoor\RoutePermission\Exceptions;

use InvalidArgumentException;

class RoutePermissionNotProperlyFormatted extends InvalidArgumentException
{
    public static function create(string $permission)
    {
        return new static("Route permission `{$permission}` is not properly formatted.");
    }
}
