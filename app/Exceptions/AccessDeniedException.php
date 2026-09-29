<?php

namespace App\Exceptions;

class AccessDeniedException extends ApiException
{
    public function status(): int
    {
        return 403;
    }
}
