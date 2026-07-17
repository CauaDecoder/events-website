<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\Auth\LogoutController;
use App\Http\Controllers\Api\V1\Auth\MeController;
use App\Http\Controllers\Api\V1\Auth\RegisterController;
use App\Http\Controllers\Api\V1\Auth\ResetPasswordController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->name('auth.')->group(function (): void {
    Route::middleware('guest')->group(function (): void {
        Route::post('/register', RegisterController::class)->name('register');
        Route::post('/login', LoginController::class)->name('login');
        Route::post('/forgot-password', ForgotPasswordController::class)
            ->middleware('throttle:6,1')
            ->name('password.email');
        Route::post('/reset-password', ResetPasswordController::class)->name('password.reset');
    });

    Route::middleware('auth:sanctum')->group(function (): void {
        Route::get('/me', MeController::class)->name('me');
        Route::post('/logout', LogoutController::class)->name('logout');
    });
});
