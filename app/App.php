<?php

namespace App;

use App\Services\ArticleService;
use App\Services\Contracts\ArticleServiceInterface;
use Src\Container;
use Src\Http\Contracts\RequestInterface;
use Src\Http\Request;
use Src\Route;
use Src\TemplateEngine\Contracts\TemplateEngineInterface;
use Src\TemplateEngine\TwigTemplateEngine;

class App
{
    public function __construct()
    {
        require_once(__DIR__ . '/Http/routes.php');
    }

    //TODO: Remove from here and create an provider
    private function binds()
    {
        Container::getInstance()->bind(RequestInterface::class, Request::class);
        Container::getInstance()->bind(ArticleServiceInterface::class, ArticleService::class);
        Container::getInstance()->bind(TemplateEngineInterface::class, TwigTemplateEngine::class);
    }

    public function run()
    {
        //TODO: Implement error handler
        try {
            $this->binds();

            $response = Route::getInstance()->dispatch();

            echo Container::getInstance()->call($response);
        } catch (\Throwable $t) {
            echo "{$t->getCode()} - {$t->getMessage()}";
        }

        exit(0);
    }
}
