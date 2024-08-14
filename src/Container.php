<?php

namespace Src;

class Container
{
    private static Container $instance;

    private array $binds = [];

    private array $loaded = [];

    private function __construct()
    {
    }

    public static function getInstance(): Container
    {
        if (isset(self::$instance)) {
            return self::$instance;
        }

        self::$instance = new self();

        return self::$instance;
    }

    public function get(string $resource, array $params = [])
    {
        if (isset($this->loaded[$resource])) {
            return $this->loaded[$resource];
        }

        if (isset($this->binds[$resource])) {
            $resource = $this->binds[$resource];
        }

        $class = new \ReflectionClass($resource);
        $constructor = $class->getConstructor();

        if (is_null($constructor)) {
            $this->loaded[$resource] = $class->newInstance();
            return $this->loaded[$resource];
        }

        if (empty($constructor->getParameters())) {
            $this->loaded[$resource] = $class->newInstance();
            return $this->loaded[$resource];
        }

        $args = [];

        foreach ($constructor->getParameters() as $arg) {
            if ($arg->getType()) {
                $args[$arg->getName()] = $this->get($arg->getType()->getName());
                continue;
            }

            $args[$arg->getName()] = $params[$arg->getName()];
        }

        $this->loaded[$resource] = $class->newInstanceArgs($args);

        return $this->loaded[$resource];
    }

    public function call(array $handler, array $params = [])
    {
        $obj = $this->get($handler[0], $params);

        $call = new \ReflectionMethod($handler[0], $handler[1]);
        return $call->invokeArgs($obj, $params);
    }

    public function bind(string $abstract, string $concrete)
    {
        $this->binds[$abstract] = $concrete;
    }
}
