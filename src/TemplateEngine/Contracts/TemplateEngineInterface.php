<?php

namespace Src\TemplateEngine\Contracts;

interface TemplateEngineInterface
{
    public function render(string $file, array $params);
}
