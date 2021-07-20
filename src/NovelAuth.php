<?php

namespace Hos3ein\NovelAuth;

use Hos3ein\NovelAuth\Contracts\AccountManager;
use Hos3ein\NovelAuth\Contracts\OtpManager;
use Hos3ein\NovelAuth\Features\Constants;
use Illuminate\Support\Str;
use Laravel\Fortify\Rules\Password;

class NovelAuth
{
    public static $onAuthDoneCallback;

    public static function onAuthDone(callable $callback)
    {
        static::$onAuthDoneCallback = $callback;
    }

    public static $redirectTo = '/';

    public static function redirectTo($path)
    {
        static::$redirectTo = $path;
    }

    private static $customValidationRules;

    public static function customValidationRules(array $rules)
    {
        static::$customValidationRules = $rules;
    }

    public static function validationRules(): array
    {
        return static::$customValidationRules
            ?: ['pass_conf' => ['nullable', 'string', (new Password)->length(8)->requireNumeric()->requireUppercase()->requireSpecialCharacter()]];
    }

    private static $customEmailPhoneValidationCallback;

    public static function emailPhoneValidationUsing(callable $callback)
    {
        static::$customEmailPhoneValidationCallback = $callback;
    }

    public static function emailPhoneValidationCallback(): \Closure
    {
        return static::$customEmailPhoneValidationCallback
            ?: function ($emailPhone) {
                if (is_numeric($emailPhone))
                    return array(Str::length($emailPhone) > 0 and Str::length($emailPhone) < 10, Constants::$PHONE_MODE);
                else
                    return array(filter_var($emailPhone, FILTER_VALIDATE_EMAIL), Constants::$EMAIL_MODE);
            };
    }

    public static function accountManagerUsing(string $callback)
    {
        app()->singleton(AccountManager::class, $callback);
    }

    public static function otpManagerUsing(string $callback)
    {
        app()->singleton(OtpManager::class, $callback);
    }

    private static $customViewPrefix = 'novel-auth::auth.';

    public static function viewPrefix(string $prefix)
    {
        static::$customViewPrefix = $prefix;
    }

    public static function authView(): string
    {
        return static::$customViewPrefix . 'auth';
    }

    public static function passesView(): string
    {
        return static::$customViewPrefix . 'passes';
    }

    public static function passView(): string
    {
        return static::$customViewPrefix . 'pass';
    }

    public static function codeView(): string
    {
        return static::$customViewPrefix . 'code';
    }

    public static function codeOptionsView(): string
    {
        return static::$customViewPrefix . 'code_options';
    }

    /**
     * Indicates if NovelAuth routes will be registered.
     *
     * @var bool
     */
    public static $registersRoutes = true;

    /**
     * Configure NovelAuth to not register its routes.
     *
     * @return static
     */
    public static function ignoreRoutes(): NovelAuth
    {
        static::$registersRoutes = false;
        return new static;
    }
}
