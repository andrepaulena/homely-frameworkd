<?php

namespace App\Repository;

use Src\Db;

abstract class BaseRepository
{
    protected string $entity;

    protected string $tableName;

    private array $query = [];

    private Db $db;

    public function __construct(Db $db)
    {
        $this->db = $db;
    }

    public function paginate(int $perPage = 3, int $page = 1): BaseRepository
    {
        if (empty($this->query['base'])) {
            $this->query['base'] = "SELECT * FROM {$this->tableName}";
        }

        $sql = "limit {$perPage}";

        if ($page > 1) {
            $offset = $perPage;

            if ($page > 2) {
                $offset = $perPage * ($page - 1);
            }

            $sql .= ' offset ' . $offset;
        }

        $this->query['paginate'] = $sql;

        return $this;
    }

    public function query(string $query): BaseRepository
    {
        $this->query['base'] = $query;

        return $this;
    }

    public function get()
    {
        $sql = $this->query['base'];

        if (!empty($this->query['paginate'])) {
            $sql .= " {$this->query['paginate']}";
        }

        $data = $this->db->query($sql)->fetchAll(\PDO::FETCH_CLASS, $this->entity);

        return $data;
    }
}
