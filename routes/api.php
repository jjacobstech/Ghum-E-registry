<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Api\v1\Auth\NewPasswordController;
use App\Http\Controllers\Api\v1\Auth\VerifyEmailController;
use App\Http\Controllers\Api\v1\Auth\RegisteredUserController;
use App\Http\Controllers\Api\v1\Auth\PasswordResetLinkController;
use App\Http\Controllers\Api\v1\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Api\v1\Auth\EmailVerificationNotificationController;

Route::get(
    'storage/test',
    function () {

        return Storage::disk('minio')->allFiles('/');
    }
);


Route::group(
    ['prefix' => 'v1',  'accept' => 'application/json'],
    function () {

        Route::get('/', function () {

            $url = url()->current();
            return response()->json([
                'status' => true,
                'message' => 'Server running on ' . $url
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

                Route::group(['prefix' => 'user'], function () {
                    require __DIR__ . '/user.php';
                });
            }
        );
    }
);
