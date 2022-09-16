<?php

namespace Timedoor\RoutePermission;

class RoutePermission
{
    /** @var array */
    const METHOD = ['GET', 'HEAD', 'POST', 'PUT', 'DELETE', 'CONNECT', 'OPTIONS', 'PATCH', 'PURGE', 'TRACE'];

    /** @var string */
    protected $uri, $method;

    /**
     * @param string $uri
     * @param string|bool $method
     */
    public function __construct(string $uri, $method = true)
    {
        if ($method === true) {
            $method = '*';
        } elseif ($method === false) {
            extract(routePermissionNameToArray($uri));
        }

        $this->uri = $uri;
        $this->method = $method;
    }

    public function getUri()
    {
        return $this->uri;
    }

    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param string|null $method
     * 
     * @return array
     */
    public function getPermissions($method = null): array
    {        
        $method = $method ?? $this->method;

        $parts = explode('/', $this->uri);

        $path = null;
        $permissions[] = getRoutePermissionName('*', $method);

        foreach ($parts as $part) {
            $path .= $part;
            $permissions[] = getRoutePermissionName($path . '/*', $method);
            $path .= '/';
        }

        if ($this->method == $method) {
            if ($method == '*') {
                foreach (self::METHOD as $method) {
                    $permissions = array_merge($permissions, $this->getPermissions($method));
                }
            } else {
                $permissions = array_merge($permissions, $this->getPermissions('*'));
            }
        }

        return $permissions;
    }

    /**
     * @return string
     */
    public function getPermission(): string
    {
        return getRoutePermissionName($this->uri, $this->method);
    }
}
