<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Global API rate limiting (matching Express 200 req/min)
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(200)->by($request->user()?->id ?: $request->ip());
        });

        // Dedicated distributed rate limiters for authentication endpoints
        RateLimiter::for('auth.login', function (Request $request) {
            $key = ($request->input('email') ?: 'guest').'|'.$request->ip();

            return Limit::perMinute(5)->by($key);
        });

        RateLimiter::for('auth.register', function (Request $request) {
            return Limit::perMinute(10)->by($request->ip());
        });

        RateLimiter::for('auth.otp', function (Request $request) {
            $key = ($request->input('email') ?: 'guest').'|'.$request->ip();

            return Limit::perMinute(5)->by($key);
        });

        RateLimiter::for('auth.resend', function (Request $request) {
            $key = ($request->input('email') ?: 'guest').'|'.$request->ip();

            return Limit::perMinutes(10, 3)->by($key);
        });
    }
}
