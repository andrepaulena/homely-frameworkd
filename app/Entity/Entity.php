<?php

namespace App\Entity;

class Entity implements \JsonSerializable
{
    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $this->{$key} = $value;
        }
    }

    protected function toCamelCase(string $string): string
    {
        $string = trim($string);

        $string = str_replace('_', ' ', $string);
        $string = lcfirst(ucwords($string));

        return str_replace(' ', '', $string);
    }

    public function __set($key, $value)
    {
        $calledClass = get_called_class();
        $attributes = get_class_vars($calledClass);
        $attrInCamelCase = $this->toCamelCase($key);

        $methods = get_class_methods($calledClass);
        $method = 'set' . ucfirst($this->toCamelCase($key));

        if (in_array($attrInCamelCase, array_keys($attributes))) {
            $type = (new \ReflectionProperty($calledClass, $attrInCamelCase))->getType()->getName();

            if ($type === 'DateTime') {
                $value = new \DateTime($value);
            }

            if (in_array($method, $methods)) {
                $this->{$method}($value);

                return;
            }

            $this->{$attrInCamelCase} = $value;
        }
    }

    public function __call(string $name, array $arguments)
    {
        $calledClass = get_called_class();
        $methods = get_class_methods($calledClass);

        if (! in_array($name, $methods)) {
            $name = lcfirst(str_replace('get', '', $name));

            return $this->{$name};
        }
    }

    public function toArray(): array
    {
        $calledClass = get_called_class();
        $attributes = get_class_vars($calledClass);

        $data = [];

        foreach ($attributes as $key => $value) {
            $method = 'get' . ucfirst($this->toCamelCase($key));
            $data[$key] = $this->{$method}();
        }

        return $data;
    }

    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }
}
