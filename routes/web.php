<?php

use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/', function (): JsonResponse {
    try {
        $dbStatus = Cache::remember('server_db_status', 10, function (): string {
            try {
                DB::connection()->getPdo();

                return 'connected';
            } catch (Throwable) {
                return 'disconnected';
            }
        });
    } catch (Throwable) {
        try {
            DB::connection()->getPdo();

            $dbStatus = 'connected';
        } catch (Throwable) {
            $dbStatus = 'disconnected';
        }
    }

    $appName = (string) config('app.name', 'PL Server');

    return ApiResponse::formatResponse([
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
            'auth' => url(trim((string) env('API_PREFIX', 'api/v1'), '/').'/auth'),
        ],
    ], "{$appName} is running smoothly.", 200);
});
