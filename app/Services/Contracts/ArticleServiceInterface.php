<?php

namespace App\Services\Contracts;

interface ArticleServiceInterface
{
    public function getPaginated(int $perPage = 3, int $page = 1);
}
