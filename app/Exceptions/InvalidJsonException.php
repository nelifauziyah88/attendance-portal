<?php

namespace App\Exceptions;

class InvalidJsonException extends ApiException
{
    public function __construct()
    {
        parent::__construct('Format request tidak valid');
    }

    public function status(): int
    {
        return 400;
    }
}
