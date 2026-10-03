<?php

namespace App\OpenApi;

use Dedoc\Scramble\Extensions\ExceptionToResponseExtension;
use Dedoc\Scramble\Support\Generator\Reference;
use Dedoc\Scramble\Support\Generator\Response;
use Dedoc\Scramble\Support\Generator\Schema;
use Dedoc\Scramble\Support\Generator\Types as OpenApiTypes;
use Dedoc\Scramble\Support\Type\ObjectType;
use Dedoc\Scramble\Support\Type\Type;
use Illuminate\Validation\ValidationException;

class ValidationExceptionToResponseExtension extends ExceptionToResponseExtension
{
    public function shouldHandle(Type $type)
    {
        return $type instanceof ObjectType
            && $type->isInstanceOf(ValidationException::class);
    }

    public function toResponse(Type $type)
    {
        $body = (new OpenApiTypes\ObjectType)
            ->addProperty('success', (new OpenApiTypes\BooleanType)->example(false))
            ->addProperty('message', (new OpenApiTypes\StringType)->example('Validasi gagal'))
            ->addProperty('data', (new OpenApiTypes\ObjectType)->additionalProperties(new OpenApiTypes\StringType))
            ->setRequired(['success', 'message', 'data']);

        return Response::make(400)
            ->setDescription('Validasi gagal')
            ->setContent('application/json', Schema::fromType($body));
    }

    public function reference(ObjectType $type)
    {
        return new Reference('responses', 'ApiValidationError', $this->components);
    }
}
