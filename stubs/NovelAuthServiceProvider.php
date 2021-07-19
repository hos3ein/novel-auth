<?php

namespace App\Providers;

use App\Actions\NovelAuth\AccountManager;
use App\Actions\NovelAuth\OtpManager;
use Hos3ein\NovelAuth\Classes\TM;
use Hos3ein\NovelAuth\NovelAuth;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Rules\Password;

class NovelAuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // NovelAuth::ignoreRoutes();
        NovelAuth::accountManagerUsing(AccountManager::class);
        NovelAuth::otpManagerUsing(OtpManager::class);

        /*NovelAuth::customValidationRules([
            'pass_conf' => ['nullable', 'string', (new Password)->length(8)->requireNumeric()->requireUppercase()->requireSpecialCharacter()]
        ]);*/
        // NovelAuth::viewPrefix('auth.');

        RateLimiter::for('auth', function (Request $request) {
            if ($request->token_rc)
                $identifier = TM::ParseToken($request->token_rc)->getClaim('email_phone', '');
            else
                $identifier = $request->email_phone;

            return Limit::perMinute(10)->by($identifier . $request->ip());
        });

        /*NovelAuth::onAuthDone(function (Request $request, $user) {
            if ($request->wantsJson()) {
                $token = auth('api-jwt')->login($user);
                return response()->json([
                    'user' => auth('api-jwt')->user(),
                    'token' => $token,
                    'message' => 'welcome',
                    'next_page' => 'home',
                ]);
            } else {
                // session base
                auth()->login($user, $request->filled('remember'));
                $token = auth()->user()->getRememberToken();
                return response()->redirectToRoute('auth.profile');
            }
        });*/
    }
}
