<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\Auth\NewPasswordController;
use App\Http\Controllers\Api\v1\Auth\VerifyEmailController;
use App\Http\Controllers\Api\v1\Auth\RegisteredUserController;
use App\Http\Controllers\Api\v1\Auth\PasswordResetLinkController;
use App\Http\Controllers\Api\v1\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Api\v1\Auth\EmailVerificationNotificationController;

Route::group(
    ['prefix' => 'v1',  'accept' => 'application/json'],
    function () {

        Route::get('/status', function () {

            $port = url();

            return response()->json([
                'status' => true,
                'message' => 'Server tunning on ' . $port
            ], 200);
        });
        Route::middleware('guest')->group(function () {
            Route::post('/register', [RegisteredUserController::class, 'store']);

            Route::post('/login', [AuthenticatedSessionController::class, 'store']);

            Route::post('/forgot-password', [PasswordResetLinkController::class, 'store']);

            Route::post('/reset-password', [NewPasswordController::class, 'store']);
        });



        Route::middleware('auth:sanctum')->group(
            function () {

                Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)
                    ->middleware(['signed', 'throttle:6,1']);

                Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
                    ->middleware(['throttle:6,1']);

                Route::post('/logout', [AuthenticatedSessionController::class, 'destroy']);

                Route::prefix('user')->group(function () {
                    require __DIR__ . '/user.php';
                });
            }
        );
    }
);