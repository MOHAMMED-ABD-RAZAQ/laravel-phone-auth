<?php

use Illuminate\Support\Facades\Route;
use Package\Auth\Controllers\AuthController;

$prefix = config('auth-package.routes.prefix', 'api/auth');
$middleware = config('auth-package.routes.middleware', ['api']);
$authMiddleware = config('auth-package.routes.auth_middleware', ['auth:sanctum']);

Route::prefix($prefix)
    ->middleware($middleware)
    ->group(function () use ($authMiddleware) {

    // Public routes
    Route::post('login', [AuthController::class, 'login'])->name('auth.login');
    Route::post('register', [AuthController::class, 'register'])->name('auth.register');
    Route::post('request-password-reset', [AuthController::class, 'requestPasswordReset'])->name('auth.request-password-reset');
    Route::post('verify-password-reset-otp', [AuthController::class, 'verifyPasswordResetOtp'])->name('auth.verify-password-reset-otp');
    Route::post('resend-password-reset-otp', [AuthController::class, 'resendPasswordResetOtp'])->name('auth.resend-password-reset-otp');

    // Protected routes
    Route::middleware($authMiddleware)->group(function () {
        Route::post('verify-otp', [AuthController::class, 'verifyOtp'])->name('auth.verify-otp');
        Route::post('resend-otp', [AuthController::class, 'resendOtp'])->name('auth.resend-otp');
        Route::post('change-password', [AuthController::class, 'changePassword'])->name('auth.change-password');
        Route::post('logout', [AuthController::class, 'logout'])->name('auth.logout');
        Route::get('profile', [AuthController::class, 'profile'])->name('auth.profile');
    });
}); 