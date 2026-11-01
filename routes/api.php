<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(function () {
        // Public registration & login with dedicated Redis distributed rate limiters
        Route::post('/register', [AuthController::class, 'register'])
            ->middleware('throttle:auth.register');

        Route::post('/login', [AuthController::class, 'login'])
            ->middleware('throttle:auth.login');

        // OTP verification
        Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])
            ->middleware('throttle:auth.otp');

        Route::post('/resend-otp', [AuthController::class, 'resendOtp'])
            ->middleware('throttle:auth.resend');

        // Role selection
        Route::match(['post', 'patch'], '/select-role', [AuthController::class, 'selectRole'])
            ->middleware('jwt.optional');

        // Password reset flow
        Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])
            ->middleware('throttle:auth.resend');

        Route::post('/forgot-otp-verify', [AuthController::class, 'forgotOtpVerify'])
            ->middleware('throttle:auth.otp');

        Route::post('/reset-password', [AuthController::class, 'resetPassword']);

        // Password change (requires valid JWT access token)
        Route::post('/change-password', [AuthController::class, 'changePassword'])
            ->middleware('jwt.auth:admin,provider,client');

        // Social Authentication
        Route::post('/google', [AuthController::class, 'googleSignIn']);
        Route::get('/google', [AuthController::class, 'googleRedirect']);
        Route::get('/google/callback', [AuthController::class, 'googleCallback']);
        Route::post('/apple', [AuthController::class, 'appleSignIn']);

        // Token management & authenticated profile
        Route::post('/refresh-token', [AuthController::class, 'refreshToken']);
        Route::get('/me', [AuthController::class, 'me'])
            ->middleware('jwt.auth:admin,provider,client');
    });
});
