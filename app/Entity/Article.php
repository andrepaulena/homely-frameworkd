<?php

namespace App\Entity;

class Article extends Entity
{
    protected int $id;

    protected string $title;

    protected string $image;

    protected string $content;

    protected string $author;

    protected \DateTime $createdAt;

    public function getTruncatedContent(int $size = 1000): string
    {
        if (strlen($this->content) > $size) {
            return substr($this->content, 0, $size) . '...';
        }

        return $this->content;
    }
}
