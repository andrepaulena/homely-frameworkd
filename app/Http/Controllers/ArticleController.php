<?php

namespace App\Http\Controllers;

use App\Services\Contracts\ArticleServiceInterface;
use Src\Http\Contracts\RequestInterface;

class ArticleController
{
    private ArticleServiceInterface $articleService;
    private RequestInterface $request;

    public function __construct(ArticleServiceInterface $articleService, RequestInterface $request)
    {
        $this->articleService = $articleService;
        $this->request = $request;
    }

    public function index()
    {
        $page = $this->request->query('page', 1);
        $perPage = $this->request->query('per_page', 3);

        $data = $this->articleService->getPaginated($perPage, $page);

        return render('index', [
            'data' => $data,
            'perPage' => $perPage,
            'page' => $page
        ]);
    }
}
