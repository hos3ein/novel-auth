<?php

require_once __DIR__ . '/../vendor/hos3ein/novel-auth/config/Constants.php';

use Hos3ein\NovelAuth\Features\Constants;

return [

    /*
    |--------------------------------------------------------------------------
    | Register Configs
    |--------------------------------------------------------------------------
    */

    Constants::$registerMethods => [
        Constants::$EMAIL_MODE,
        Constants::$PHONE_MODE
    ],
    Constants::$registerMode => Constants::$CP_CODE_PASSWORD,
    Constants::$registerPhoneOptServices => [
        Constants::$OTP_CALL,
        Constants::$OTP_SMS,
        Constants::$OTP_USSD
    ],
    Constants::$defaultRegisterPhoneOptService => Constants::$OTP_SMS,

    /*
    |--------------------------------------------------------------------------
    | Login Configs
    |--------------------------------------------------------------------------
    */

    Constants::$loginMode => Constants::$OPTIONAL_CODE_PASSWORD,

    /*
    |--------------------------------------------------------------------------
    | OTP Services Configs
    |--------------------------------------------------------------------------
    */

    Constants::$otpServices => [
        Constants::$OTP_EMAIL,
        Constants::$OTP_CALL,
        Constants::$OTP_SMS,
        Constants::$OTP_USSD,
        Constants::$OTP_TELEGRAM,
        Constants::$OTP_WHATSAPP,
        Constants::$OTP_GENERATOR
    ],

    Constants::$emailTTL => 30, // seconds
    Constants::$callTTL => 60,
    Constants::$smsTTL => 90,
    Constants::$telegramTTL => 10,
    Constants::$whatsappTTL => 20,

    /*
    |--------------------------------------------------------------------------
    |
    |--------------------------------------------------------------------------
    */

    Constants::$tokenExpiration => '+5 hour',
    Constants::$otpCodeTtl => '2 hour', // acceptable code up to 2 hours
    Constants::$encryptOtpCode => true,

    /*
    |--------------------------------------------------------------------------
    | NovelAuth Routes Prefix / Subdomain
    |--------------------------------------------------------------------------
    |
    | Here you may specify which prefix NovelAuth will assign to all the routes
    | that it registers with the application. If necessary, you may change
    | subdomain under which all of the NovelAuth routes will be available.
    |
    */

    Constants::$prefix => '',
    Constants::$domain => null,

    /*
    |--------------------------------------------------------------------------
    |
    |--------------------------------------------------------------------------
    */

    Constants::$home => '/dashboard',
    Constants::$views => true,
    Constants::$middleware => ['web'],
    Constants::$guard => 'web',
    Constants::$limiters => [
        Constants::$limitersAuth => 'auth'
    ],
    Constants::$secretKey => config('app.key'),
];
