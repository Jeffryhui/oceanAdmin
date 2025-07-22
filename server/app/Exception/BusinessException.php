<?php

namespace App\Exception;

use Hyperf\Server\Exception\ServerException;

class BusinessException extends ServerException
{
    public function __construct(string $message = 'Error', int $code = 400, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
