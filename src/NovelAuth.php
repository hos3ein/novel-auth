<?php

namespace Hos3ein\NovelAuth;

use Hos3ein\NovelAuth\Contracts\AccountManager;
use Hos3ein\NovelAuth\Contracts\OtpManager;
use Laravel\Fortify\Rules\Password;

class NovelAuth
{
    public static $onAuthDoneCallback;

    public static function onAuthDone(callable $callback)
    {
        static::$onAuthDoneCallback = $callback;
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

    public static function profileView(): string
    {
        return static::$customViewPrefix . 'profile';
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
