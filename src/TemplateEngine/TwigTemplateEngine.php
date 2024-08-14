<?php

namespace Src\TemplateEngine;

use Src\TemplateEngine\Contracts\TemplateEngineInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class TwigTemplateEngine implements TemplateEngineInterface
{
    private Environment $twig;

    public function __construct()
    {
        $basePath = dirname(__DIR__);

        $loader = new FilesystemLoader('../resources/views', $basePath);
        $this->twig = new Environment($loader, [
            'cache' => false,
        ]);
    }

    public function render(string $file, array $params = [])
    {
        if (strpos($file, '.twig') === false) {
            $file .= '.twig';
        }

        return $this->twig->render($file, $params);
    }
}
