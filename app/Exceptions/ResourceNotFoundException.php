<?php

namespace App\Exceptions;

class ResourceNotFoundException extends ApiException
{
    public function status(): int
    {
        return 404;
    }
}
