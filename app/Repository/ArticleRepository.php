<?php

namespace App\Repository;

use App\Entity\Article;

class ArticleRepository extends BaseRepository
{
    protected string $entity = Article::class;
    protected string $tableName = 'articles';
}
