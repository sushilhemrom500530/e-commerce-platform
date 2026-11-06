<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StorefrontController;
use App\Http\Controllers\SubcategoryController;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

// Public Storefront
Route::get('/', [StorefrontController::class, 'index'])->name('storefront.index');
Route::get('/products', [StorefrontController::class, 'products'])->name('storefront.products');
Route::get('/product/{product:slug}', [StorefrontController::class, 'show'])->name('storefront.product');

// Server Health Route
Route::get('/health', function (): JsonResponse {
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
            'health' => url('/health'),
            'auth' => url(trim((string) env('API_PREFIX', 'api/v1'), '/').'/auth'),
        ],
    ], "{$appName} is running smoothly.", 200);
})->name('health');

// Admin Management Portal
Route::prefix('admin')->name('admin.')->group(function () {
    Route::redirect('/', '/admin/products')->name('dashboard');

    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::resource('subcategories', SubcategoryController::class)->except(['show']);
    Route::get('categories/{category}/subcategories', [SubcategoryController::class, 'byCategory'])->name('categories.subcategories');
    Route::resource('products', ProductController::class)->except(['show']);
});
