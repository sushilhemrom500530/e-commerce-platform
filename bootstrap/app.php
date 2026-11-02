<?php

use App\Http\Middleware\ApiRequestLogger;
use App\Http\Middleware\JwtAuthenticate;
use App\Http\Middleware\OptionalJwtAuthenticate;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        apiPrefix: env('API_PREFIX', 'api/v1'),
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');
        $middleware->append(ApiRequestLogger::class);

        $middleware->alias([
            'jwt.auth' => JwtAuthenticate::class,
            'jwt.optional' => OptionalJwtAuthenticate::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn(Request $request) => $request->is('api/*') || $request->expectsJson(),
        );

        $exceptions->render(function (ValidationException $e, Request $request) {
            $errorMessages = [];
            foreach ($e->errors() as $field => $messages) {
                foreach ($messages as $message) {
                    $errorMessages[] = [
                        'path' => $field,
                        'message' => $message,
                    ];
                }
            }

            return response()->json([
                'success' => false,
                'statusCode' => 400,
                'message' => 'Validation Error',
                'errorMessages' => $errorMessages,
            ], 400);
        });

        $exceptions->render(function (AuthenticationException $e, Request $request) {
            return response()->json([
                'success' => false,
                'statusCode' => 401,
                'message' => 'Access denied. No token provided.',
                'errorMessages' => [
                    ['path' => '', 'message' => $e->getMessage() ?: 'Access denied. No token provided.'],
                ],
            ], 401);
        });

        $exceptions->render(function (AccessDeniedHttpException $e, Request $request) {
            return response()->json([
                'success' => false,
                'statusCode' => 403,
                'message' => 'You are not authorized',
                'errorMessages' => [
                    ['path' => '', 'message' => $e->getMessage() ?: 'You are not authorized'],
                ],
            ], 403);
        });

        $exceptions->render(function (ThrottleRequestsException $e, Request $request) {
            return response()->json([
                'success' => false,
                'statusCode' => 429,
                'message' => 'Too many requests from this IP, please try again later',
                'errorMessages' => [
                    ['path' => '', 'message' => 'Too many requests. Please try again later.'],
                ],
            ], 429, $e->getHeaders());
        });

        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            return response()->json([
                'success' => false,
                'statusCode' => 404,
                'message' => 'Resource not found',
                'errorMessages' => [
                    ['path' => '', 'message' => $e->getMessage() ?: 'Resource not found'],
                ],
            ], 404);
        });
    })->create();
