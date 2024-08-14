<?php

namespace App\Entity;

class Entity
{
    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $this->{$key} = $value;
        }
    }

    protected function toCamelCase($string)
    {
        $string = str_replace('_', ' ', $string);
        $string = lcfirst(ucwords($string));
        $string = str_replace(' ', '', $string);

        return $string;
    }

    //TODO: Improve set when type was a date
    public function __set($key, $value)
    {
        if ($key === 'created_at') {
            $this->setCreatedAt(new \DateTime($value));
            return;
        }

        $methods = get_class_methods(get_called_class());

        $method = 'set' . ucfirst($this->toCamelCase($key));

        if (in_array($method, $methods)) {
            $this->{$method}($value);
        }
    }
}
