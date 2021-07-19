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

    'prefix' => '',

    'domain' => null,

    /*
    |--------------------------------------------------------------------------
    */

//    'registerMethods' => ['e', 'm'],
    'register_methods' => [Constants::$EMAIL_MODE, Constants::$PHONE_MODE],
    'registerCodePassword' => 'code_password',
//    'registerPhoneOptServices' => ['call', 'sms', 'ussd'],
    'register_phone_opt_services' => [Constants::$OTP_CALL, Constants::$OTP_SMS, Constants::$OTP_USSD],
    'defaultRegisterPhoneOptService' => 'sms',

    'loginOptions' => 'option_password_code',
//    'optServices' => ['email', 'call', 'sms', 'ussd', 'telegram', 'whatsapp', 'otp_generator'],
    'opt_services' => [Constants::$OTP_EMAIL, Constants::$OTP_CALL, Constants::$OTP_SMS, Constants::$OTP_USSD, Constants::$OTP_TELEGRAM, Constants::$OTP_WHATSAPP, Constants::$OTP_GENERATOR],

    'encrypt_otp_code' => true,

    'emailTTL' => 30, // seconds
    'callTTL' => 20,
    'smsTTL' => 3,
    'telegramTTL' => 5,
    'whatsappTTL' => 7,

    'tokenExpiration' => '+5 hour',
    'otpCodeTtl' => '5 minute', // acceptable code up to 2 hours

    'middleware' => ['web'],
    'guard' => 'web',
    'views' => true,
    'limiters' => ['auth' => 'auth'],
    'secret-key' => config('app.key'),
];
