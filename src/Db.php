<?php

namespace Src;

class Db
{
    private \PDO $connection;

    //TODO: Add connection variables to a .env file
    public function __construct()
    {
        $this->connection = new \PDO('mysql:host=blog-db;dbname=blog_db', 'blog', 'blog');
    }

    public function query(string $query)
    {
        return $this->connection->query($query);
    }

    public function getConnection(): \PDO
    {
        return $this->connection;
    }
}
