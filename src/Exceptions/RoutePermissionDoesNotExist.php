<?php

namespace Timedoor\RoutePermission\Exceptions;

use InvalidArgumentException;

class RoutePermissionDoesNotExist extends InvalidArgumentException
{
    public static function related(string $uri, $method = '*', $guardName = '')
    {
        return new static("There is no route permission related to `{$uri}` URI with `{$method}` method for guard `{$guardName}`.");
    }
}
