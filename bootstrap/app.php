<?php

use App\Exceptions\ApiException;
use App\Http\Middleware\EnsureValidJson;
use App\Http\Responses\ApiResponse;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->api(prepend: [
            EnsureValidJson::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $isApi = fn (Request $request) => $request->is('api', 'api/*');

        $exceptions->shouldRenderJsonWhen(fn (Request $request) => $isApi($request) || $request->expectsJson());

        $exceptions->render(function (ApiException $exception) {
            return ApiResponse::error($exception->getMessage(), $exception->status());
        });

        $exceptions->render(function (ValidationException $exception, Request $request) use ($isApi) {
            if (! $isApi($request)) {
                return null;
            }

            $errors = collect($exception->errors())
                ->map(fn (array $messages) => $messages[0])
                ->all();

            return ApiResponse::error('Validasi gagal', 400, $errors);
        });

        $exceptions->render(function (BadRequestHttpException $exception, Request $request) use ($isApi) {
            if (! $isApi($request)) {
                return null;
            }

            return ApiResponse::error('Format request tidak valid', 400);
        });

        $exceptions->render(function (NotFoundHttpException $exception, Request $request) use ($isApi) {
            if (! $isApi($request)) {
                return null;
            }

            return ApiResponse::error('Endpoint tidak ditemukan', 404);
        });

        $exceptions->render(function (MethodNotAllowedHttpException $exception, Request $request) use ($isApi) {
            if (! $isApi($request)) {
                return null;
            }

            return ApiResponse::error('Metode tidak diizinkan', 405);
        });

        $exceptions->render(function (HttpExceptionInterface $exception, Request $request) use ($isApi) {
            if (! $isApi($request)) {
                return null;
            }

            return ApiResponse::error($exception->getMessage() ?: 'Terjadi kesalahan', $exception->getStatusCode());
        });

        $exceptions->render(function (Throwable $exception, Request $request) use ($isApi) {
            if (! $isApi($request)) {
                return null;
            }

            return ApiResponse::error('Terjadi kesalahan pada server', 500);
        });
    })->create();
