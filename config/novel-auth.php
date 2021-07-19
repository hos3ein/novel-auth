<?php

namespace Hos3ein\NovelAuth\Features;
require_once __DIR__ . '/Constants.php';

return [
    Constants::$registerMethods => [Constants::$EMAIL_MODE, Constants::$PHONE_MODE],
    Constants::$registerCodePassword => Constants::$CP_CODE_PASSWORD,
    Constants::$registerPhoneOptServices => [Constants::$OTP_CALL, Constants::$OTP_SMS, Constants::$OTP_USSD],
    Constants::$defaultRegisterPhoneOptService => null,

    Constants::$loginOptions => Constants::$OPTION_PASSWORD_CODE,
    Constants::$optServices => [Constants::$OTP_EMAIL, Constants::$OTP_CALL, Constants::$OTP_SMS, Constants::$OTP_USSD, Constants::$OTP_TELEGRAM, Constants::$OTP_WHATSAPP, Constants::$OTP_GENERATOR],

    Constants::$encryptOtpCode => true,

    Constants::$emailTTL => 30, // seconds
    Constants::$callTTL => 20,
    Constants::$smsTTL => 3,
    Constants::$telegramTTL => 5,
    Constants::$whatsappTTL => 7,

    Constants::$tokenExpiration => '+5 hour',
    Constants::$otpCodeTtl => '5 minute', // acceptable code up to 2 hours

    Constants::$prefix => '',
    Constants::$domain => null,
    Constants::$middleware => ['web'],
    Constants::$guard => 'web',
    Constants::$views => true,
    Constants::$limiters => [Constants::$limitersAuth => 'auth'],
];
