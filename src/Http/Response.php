<?php

namespace Src\Http;

use Src\Http\Contracts\ResponseInterface;
use Src\TemplateEngine\Contracts\TemplateEngineInterface;

class Response implements ResponseInterface
{
    public function json(object|array $data)
    {
        header('Content-type: application/json');

        echo json_encode($data);
    }

    public function view(string $file, $data = [])
    {
        return app()->get(TemplateEngineInterface::class)->render($file, $data);
    }
}
