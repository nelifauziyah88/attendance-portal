<?php

namespace App\Exceptions;

class ConflictException extends ApiException
{
    public function status(): int
    {
        return 409;
    }
}
