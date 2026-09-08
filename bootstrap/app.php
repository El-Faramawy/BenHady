<?php

use App\Exceptions\BusinessException;
use App\Http\Middleware\Localization;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        apiPrefix: '',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'lang' => Localization::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {

        // Handle all BusinessException subclasses with a consistent API format
        $exceptions->render(function (BusinessException $e, Request $request): JsonResponse {
            Log::error($e->getErrorKey(), [
                'exception' => $e,
                'url' => $request->fullUrl(),
            ]);

            return new JsonResponse(
                [
                    'code' => $e->getStatusCode(),
                    'errors' => [__($e->getErrorKey())],
                    'data' => null,
                    'messages' => [],
                ],
                $e->getStatusCode()
            );
        });

        // Handle AuthenticationException with standard 401 format
        $exceptions->render(function (AuthenticationException $e, Request $request): JsonResponse {
            return new JsonResponse(
                [
                    'code' => 401,
                    'errors' => [__('messages.auth.unauthenticated')],
                    'data' => null,
                    'messages' => [],
                ],
                401
            );
        });

        // Handle ValidationException with standard 422 format
        $exceptions->render(function (ValidationException $e, Request $request): JsonResponse {
            $formattedErrors = [];
            foreach ($e->errors() as $fieldErrors) {
                foreach ($fieldErrors as $msg) {
                    $formattedErrors[] = $msg;
                }
            }

            return new JsonResponse(
                [
                    'code' => 422,
                    'errors' => $formattedErrors,
                    'data' => null,
                    'messages' => [],
                ],
                422
            );
        });

        // Catch-all for unhandled exceptions — return raw error in test/debug env
        $exceptions->render(function (Throwable $e, Request $request): ?JsonResponse {
            if ($request->expectsJson() || $request->is('*')) {
                Log::error('Unhandled exception', [
                    'exception' => $e,
                    'url' => $request->fullUrl(),
                ]);

                $isTestMode = in_array(config('app.env'), ['test', 'local', 'testing'], true) || (bool) config('app.debug');
                $errorMessage = $isTestMode ? $e->getMessage() : __('messages.general.server_error');

                return new JsonResponse(
                    [
                        'code' => 500,
                        'errors' => [$errorMessage],
                        'data' => null,
                        'messages' => [],
                    ],
                    500
                );
            }

            return null;
        });

    })->create();
