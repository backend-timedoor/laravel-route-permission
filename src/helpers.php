<?php

use Timedoor\RoutePermission\Exceptions\RoutePermissionNotProperlyFormatted;

if (! function_exists('getRoutePermissionName')) {
    /**
     * @param string $uri
     * @param string $method
     *
     * @return string
     */
    function getRoutePermissionName($uri, $method = "*") 
    {        
        $config = getRoutePermissionPrefix();
        $prefix = $config['prefix'];
        $separator = $config['separator'];
        $uri = trim($uri);
        $uri = trim($uri, '/');

        if (config('permission.route.convert_parameter')) {
            $uri = replaceRouteParameter($uri);
        }

        return $prefix.$separator.$uri.$separator.strtoupper($method);
    }
}

if (! function_exists('replaceRouteParameter')) {
    /**
     * @param string $uri
     *
     * @return string
     */
    function replaceRouteParameter($uri) 
    {     
        $parameter = config('permission.route.parameter');   
        $parts = explode('/', $uri);

        foreach ($parts as $key => $part) {
            if (preg_match('/^{\w+}$/', $part)) {
                $parts[$key] = "{{$parameter}}";
            }
        }

        return implode('/', $parts);
    }
}

if (! function_exists('getRoutePermissionPrefix')) {
    /**
     * @param bool $concat
     *
     * @return array|string
     */
    function getRoutePermissionPrefix($concat = false) 
    {        
        $prefix = config('permission.route.prefix');
        $separator = config('permission.route.separator');

        if ($concat) {
            return $prefix.$separator;
        }

        return compact('prefix', 'separator');
    }
}

if (! function_exists('routePermissionNameToArray')) {
    /**
     * @param string $permission
     *
     * @return array|bool
     */
    function routePermissionNameToArray($permission) 
    {        
        $prefix = getRoutePermissionPrefix()['prefix'];
        $separator = getRoutePermissionPrefix()['separator'];

        try {
            if (strpos($permission, $prefix.$separator) === 0) {
                list($prefix, $uri, $method) = explode($separator, $permission);
    
                return compact('uri', 'method');
            }
            
            throw RoutePermissionNotProperlyFormatted::create($permission);
        } catch (\Exception $e) {
            throw RoutePermissionNotProperlyFormatted::create($permission);
        }
    }
}

if (! function_exists('getRoutePermissionNameArray')) {
    /**
     * @param array $permissions
     * @param string $method
     *
     * @return \Illuminate\Support\Collection
     */
    function getRoutePermissionNameArray($permissions) 
    {       
        return collect($permissions)
            ->map(function ($permission) {
                $uri = $permission['uri'];
                $method = $permission['method'] ?? '*';

                return getRoutePermissionName($uri, $method);
            });
    }
}