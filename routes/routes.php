<?php

use Hos3ein\NovelAuth\Features\Constants;
use Hos3ein\NovelAuth\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::group(['as' => 'auth.', 'middleware' => config(Constants::$configMiddleware, ['web'])], function () {
    $enableViews = config(Constants::$configViews, true);

    Route::get('/logout', [AuthController::class, 'destroy'])->name('logout');

    if ($enableViews) {
        Route::get('/auth', [AuthController::class, 'create'])
            ->middleware(['guest:' . config(Constants::$configGuard)])
            ->name('auth');
    }

    $limiter = config(Constants::$configLimitersAuth);
    Route::post('/auth', [AuthController::class, 'store'])
        ->middleware(array_filter([
            'guest:' . config(Constants::$configGuard),
            $limiter ? 'throttle:' . $limiter : null,
        ]))
        ->name('attempt');


    /*Route::get('/2fa', function (Request $request) {
        //app(\Laravel\Fortify\Actions\EnableTwoFactorAuthentication::class)($request->user());
        return $request->user()->twoFactorQrCodeUrl();
    })->middleware(['auth']);
    Route::get('/qr', [TwoFactorQrCodeController::class, 'show'])
        ->middleware(['auth']);
    Route::get('/rc', [RecoveryCodeController::class, 'index'])
        ->middleware(['auth']);*/
});
