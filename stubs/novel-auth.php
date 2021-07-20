<?php

require_once __DIR__ . '/../vendor/hos3ein/novel-auth/config/Constants.php';

use Hos3ein\NovelAuth\Features\Constants;

return [

    /*
    |--------------------------------------------------------------------------
    | Fortify Routes Prefix / Subdomain
    |--------------------------------------------------------------------------
    |
    | Here you may specify which prefix Fortify will assign to all the routes
    | that it registers with the application. If necessary, you may change
    | subdomain under which all of the Fortify routes will be available.
    |
    */
    Constants::$prefix => '',
    Constants::$domain => null,

    /*
    |--------------------------------------------------------------------------
    */

    Constants::$registerMethods => [Constants::$EMAIL_MODE, Constants::$PHONE_MODE],
    Constants::$registerCodePassword => Constants::$CP_ONLY_PASSWORD,
    Constants::$registerPhoneOptServices => [Constants::$OTP_CALL, Constants::$OTP_SMS, Constants::$OTP_USSD],
    Constants::$defaultRegisterPhoneOptService => Constants::$OTP_SMS,

    Constants::$loginMode => Constants::$OPTIONAL_PASSWORD_CODE,
    Constants::$otpServices => [Constants::$OTP_EMAIL, Constants::$OTP_CALL, Constants::$OTP_SMS, Constants::$OTP_USSD, Constants::$OTP_TELEGRAM, Constants::$OTP_WHATSAPP, Constants::$OTP_GENERATOR],

    Constants::$encryptOtpCode => true,

    Constants::$emailTTL => 30, // seconds
    Constants::$callTTL => 20,
    Constants::$smsTTL => 3,
    Constants::$telegramTTL => 5,
    Constants::$whatsappTTL => 7,

    Constants::$tokenExpiration => '+5 hour',
    Constants::$otpCodeTtl => '2 hour', // acceptable code up to 2 hours

    Constants::$middleware => ['web'],
    Constants::$guard => 'web',
    Constants::$views => true,
    Constants::$limiters => [Constants::$limitersAuth => 'auth'],
    Constants::$secretKey => config('app.key'),
    Constants::$home => '/dashboard',
];
