<?php

namespace App\Repository;

use Src\Db;
use Src\Http\Contracts\RequestInterface;

abstract class BaseRepository
{
    private RequestInterface $request;

    protected string $entity;

    protected string $tableName;

    private array $query = [];

    private bool $paginated = false;

    private int $perPage = 10;
    private int $page = 1;

    private Db $db;

    public function __construct(Db $db, RequestInterface $request)
    {
        $this->request = $request;
        $this->db = $db;
    }

    public function paginate(?int $perPage = null, ?int $page = null): BaseRepository
    {
        $this->paginated = true;

        $this->perPage = $perPage ?? $this->request->query('per_page', 10);
        $this->page = $page ?? $this->request->query('page', 1);

        if (empty($this->query['base'])) {
            $this->query['base'] = "SELECT * FROM {$this->tableName}";
        }

        $sql = "limit {$this->perPage}";

        if ($this->page > 1) {
            $offset = $this->perPage;

            if ($this->page > 2) {
                $offset = $this->perPage * ($this->page - 1);
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

        if (! $this->paginated) {
            return $data;
        }

        return [
            'data' => $data,
            'per_page' => $this->perPage,
            'page' => $this->page
        ];
    }
}
