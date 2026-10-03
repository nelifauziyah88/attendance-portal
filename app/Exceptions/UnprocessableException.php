<?php

namespace App\Exceptions;

class UnprocessableException extends ApiException
{
    public function status(): int
    {
        return 422;
    }
}
