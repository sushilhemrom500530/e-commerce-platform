<?php

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/', function (): JsonResponse {
    $dbStatus = 'unknown';

    try {
        DB::connection()->getPdo();
        $dbStatus = 'connected';
    } catch (Throwable) {
        $dbStatus = 'disconnected';
    }

    $appName = (string) config('app.name', 'PL Server');

    return response()->json([
        'statusCode' => 200,
        'success' => true,
        'message' => "{$appName} is running smoothly.",
        'data' => [
            'name' => $appName,
            'status' => 'online',
            'environment' => config('app.env', 'production'),
            'version' => '1.0.0',
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'timestamp' => now()->toIso8601String(),
            'services' => [
                'database' => $dbStatus,
            ],
            'endpoints' => [
                'health' => url('/up'),
                'auth' => url('/api/v1/auth'),
            ],
        ],
    ], 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
});
