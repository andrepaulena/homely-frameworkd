<?php

use Src\Container;
use Src\Http\Contracts\RequestInterface;
use Src\TemplateEngine\Contracts\TemplateEngineInterface;

if (!function_exists('dd')) {
    function dd(...$params)
    {
        echo "<pre>";
        foreach ($params as $param) {
            var_dump($param);
        }
        echo "</pre>";
        exit(0);
    }
}

if (!function_exists('app')) {
    function app(): Container
    {
        return Container::getInstance();
    }
}

if (!function_exists('request')) {
    function request(): RequestInterface
    {
        return app()->get(RequestInterface::class);
    }
}

if (!function_exists('render')) {
    function render(string $file, $data = []): string
    {
        return app()->get(TemplateEngineInterface::class)->render($file, $data);
    }
}
