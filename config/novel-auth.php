<?php

namespace Hos3ein\NovelAuth\Features;
require_once __DIR__ . '/Constants.php';

return [
    Constants::$registerMethods => [],
    Constants::$registerMode => Constants::$CP_ONLY_PASSWORD,
    Constants::$registerPhoneOptServices => [],
    Constants::$defaultRegisterPhoneOptService => null,

    Constants::$loginMode => Constants::$OPTIONAL_PASSWORD_CODE,
    Constants::$otpServices => [],

    Constants::$encryptOtpCode => true,

    Constants::$emailTTL => 30, // seconds
    Constants::$callTTL => 20,
    Constants::$smsTTL => 3,
    Constants::$telegramTTL => 5,
    Constants::$whatsappTTL => 7,

    Constants::$tokenExpiration => '+5 hour',
    Constants::$otpCodeTtl => '2 hour', // acceptable code up to 2 hours

    Constants::$prefix => '',
    Constants::$domain => null,
    Constants::$middleware => ['web'],
    Constants::$guard => 'web',
    Constants::$views => true,
    Constants::$limiters => [Constants::$limitersAuth => 'auth'],
    Constants::$secretKey => config('app.key'),
    Constants::$home => '/dashboard',
];
