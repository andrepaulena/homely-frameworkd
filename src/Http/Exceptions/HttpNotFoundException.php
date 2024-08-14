<?php

namespace Src\Http\Exceptions;

class HttpNotFoundException extends \Exception
{
    protected $code = 404;
    protected $message = 'Not found';
}
