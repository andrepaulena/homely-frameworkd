<?php

namespace Src\Http\Contracts;

interface ResponseInterface
{
    public function json(array | object $data);

    public function view(string $file, $data = []);
}
