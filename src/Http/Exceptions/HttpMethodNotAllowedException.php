<?php

namespace Src\Http\Exceptions;

use Throwable;

class HttpMethodNotAllowedException extends \Exception
{
    public function __construct($message = "", Throwable $previous = null)
    {
        parent::__construct($message, 406, $previous);
    }
}
