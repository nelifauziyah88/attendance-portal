<?php

namespace App\Exceptions;

use RuntimeException;

abstract class ApiException extends RuntimeException
{
    abstract public function status(): int;
}
