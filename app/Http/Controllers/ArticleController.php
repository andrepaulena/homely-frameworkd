<?php

namespace App\Http\Controllers;

use App\Services\Contracts\ArticleServiceInterface;
use Src\Http\Contracts\ResponseInterface;

class ArticleController
{
    private ArticleServiceInterface $articleService;

    private ResponseInterface $response;

    public function __construct(ArticleServiceInterface $articleService, ResponseInterface $response)
    {
        $this->articleService = $articleService;
        $this->response = $response;
    }

    public function index()
    {
        $data = $this->articleService->getPaginated();

        return $this->response->json($data);
    }
}
