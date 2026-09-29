<?php

namespace App\Http\Middleware;

use App\Exceptions\InvalidJsonException;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureValidJson
{
    public function handle(Request $request, Closure $next): Response
    {
        $content = $request->getContent();

        if ($request->isJson() && trim($content) !== '') {
            $decoded = json_decode($content, true);

            if (json_last_error() !== JSON_ERROR_NONE || ! is_array($decoded)) {
                throw new InvalidJsonException;
            }
        }

        return $next($request);
    }
}
