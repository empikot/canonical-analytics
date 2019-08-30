<?php

namespace App\IO\Exceptions;

use Throwable;

class DotEnvFileWasNotFoundException extends \Exception
{
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct('.env file wasn\'t found', $code, $previous);
    }
}
