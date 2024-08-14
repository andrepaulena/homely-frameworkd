<?php

namespace Src\Http;

use Src\Http\Contracts\RequestInterface;

class Request implements RequestInterface
{
    private array $post = [];

    private array $query = [];

    public function __construct()
    {
        $this->post = $_POST;
        $this->query = $_GET;
    }

    public function query(?string $key = null, $default = null)
    {
        if (!is_null($key)) {
            return isset($this->query[$key]) ? $this->query[$key] : $default;
        }

        return $this->query;
    }

    public function post(?string $key = null, $default = null)
    {
        if (empty($this->post)) {
            $jsonParams = json_decode(file_get_contents("php://input"), true);

            if ($jsonParams) {
                $this->post = $jsonParams;
            }
        }

        if (!is_null($key)) {
            return isset($this->post[$key]) ? $this->post[$key] : $default;
        }

        return $this->post;
    }
}
