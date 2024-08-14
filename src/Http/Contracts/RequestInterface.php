<?php

namespace Src\Http\Contracts;

interface RequestInterface
{
    public function query(?string $key = null, $default = null);

    public function post(?string $key = null, $default = null);
}
