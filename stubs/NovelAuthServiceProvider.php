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

        // NovelAuth::customPassValidationRule((new Password())->length(8)->requireNumeric()->requireUppercase()->requireSpecialCharacter());
        // NovelAuth::customPassValidationRule('min:6');
        // NovelAuth::viewPrefix('auth.');

        /*NovelAuth::emailPhoneValidationUsing(function ($emailPhone) {
            if (is_numeric($emailPhone))
                return array(Str::length($emailPhone) > 0 and Str::length($emailPhone) < 10, Constants::$PHONE_MODE);
            else
                return array(filter_var($emailPhone, FILTER_VALIDATE_EMAIL), Constants::$EMAIL_MODE);
        });*/

        /*NovelAuth::incompleteEmailPhoneUsing(function ($otpType, $emailPhone) {
            return substr($emailPhone, 0, 1) . '***' . substr($emailPhone, -1, 1);
        });*/

        RateLimiter::for('auth', function (Request $request) {
            if ($request->token_rc)
                $identifier = TM::ParseToken($request->token_rc)->getClaim('email_phone', '');
            else
                $identifier = $request->email_phone;

            return Limit::perMinute(10)->by($identifier . $request->ip());
        });

        /*NovelAuth::onAuthDone(function (Request $request, $user) {
            if (config(Constants::$configGuard) == 'api-jwt') {
                $token = auth('api-jwt')->login($user);
                return response()->json([
                    'user' => auth('api-jwt')->user(),
                    'token' => $token,
                    'message' => 'welcome',
                    'next_page' => 'home',
                ]);
            } else {
                // session base
                auth(config(Constants::$configGuard))->login($user, $request->filled('remember'));
                $token = auth(config(Constants::$configGuard))->user()->getRememberToken();
                return response()->redirectTo(config(Constants::$configHome));
            }
        });*/
    }
}
