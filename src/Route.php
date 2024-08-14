<?php

namespace Src;

use Src\Http\Exceptions\HttpMethodNotAllowedException;
use Src\Http\Exceptions\HttpNotFoundException;

class Route
{
    private array $routes = [];

    private static Route $instance;

    private array $allowedVerbs = [
        'GET',
        'POST'
    ];

    private function __construct()
    {
    }

    public static function get(string $route, $handler)
    {
        self::getInstance()->add('GET', $route, $handler);
    }

    public static function post(string $route, $handler)
    {
        self::getInstance()->add('POST', $route, $handler);
    }

    public function add(string $method, string $route, $handler)
    {
        if (!in_array($method, $this->allowedVerbs)) {
            throw new HttpMethodNotAllowedException("Method {$method} not allowed");
        }

        $this->routes[$method][] = [
            'route' => trim($route, '/'),
            'handler' => $handler
        ];
    }

    public static function getInstance(): Route
    {
        if (isset(self::$instance)) {
            return self::$instance;
        }

        self::$instance = new self();

        return self::$instance;
    }

    public function dispatch()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];

        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }

        $uri = trim(rawurldecode($uri), '/');

        if (!isset($this->routes[$method])) {
            throw new HttpMethodNotAllowedException("Method {$method} not allowed");
        }

        foreach ($this->routes[$method] as $route) {
            if ($route['route'] == $uri) {
                return $route['handler'];
            }
        }

        throw new HttpNotFoundException();
    }
}
