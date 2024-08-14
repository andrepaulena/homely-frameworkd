<?php

namespace App\Services;

use App\Repository\ArticleRepository;
use App\Services\Contracts\ArticleServiceInterface;

class ArticleService implements ArticleServiceInterface
{
    private ArticleRepository $articleRepository;

    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    //TODO: return object with total of records and pages
    public function getPaginated(int $perPage = 3, int $page = 1)
    {
        return $this->articleRepository
            ->query('select a.*, au.name as author from articles as a 
                            join authors as au on a.author_id = au.id 
                            order by a.created_at desc')
            ->paginate($perPage, $page)
            ->get();
    }
}
