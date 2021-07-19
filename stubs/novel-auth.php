<?php

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
    'registerMethods' => ['e', 'm'],
    'registerCodePassword' => 'code_password',
    'registerPhoneOptServices' => ['call', 'sms', 'ussd'],
    'defaultRegisterPhoneOptService' => 'sms',

    'loginOptions' => 'option_password_code',
    'optServices' => ['email', 'call', 'sms', 'ussd', 'telegram', 'whatsapp', 'otp_generator'],

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
];
