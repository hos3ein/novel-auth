<?php

namespace Hos3ein\NovelAuth\Features;


class Constants
{
    // user status
    public static $STATUS_INCOMPLETE_REGISTRATION = -1;
    public static $STATUS_DISABLE = 0;
    public static $STATUS_ACTIVE = 1;

    public static $configFile = 'novel-auth.';

    // -------------------------------------------------------------------------------------
    public static $configRegisterMethods = 'novel-auth.register_methods';
    public static $registerMethods = 'register_methods';
    public static $EMAIL_MODE = 'e';
    public static $PHONE_MODE = 'm';

    public static $configRegisterMode = 'novel-auth.register_mode';
    public static $registerMode = 'register_mode';
    public static $CP_ONLY_CODE = 'code';
    public static $CP_ONLY_PASSWORD = 'password';
    public static $CP_CODE_PASSWORD = 'code_password';

    public static $configRegisterPhoneOptServices = 'novel-auth.register_phone_opt_services';
    public static $registerPhoneOptServices = 'register_phone_opt_services'; // check that only call, sms, ussd
    public static $configDefaultRegisterPhoneOptServices = 'novel-auth.default_register_phone_opt_services';
    public static $defaultRegisterPhoneOptService = 'default_register_phone_opt_services'; // nullable - check valid values

    // -------------------------------------------------------------------------------------
    public static $configLoginMode = 'novel-auth.login_mode';
    public static $loginMode = 'login_mode'; // nullable (mean disable login)
    public static $ONLY_PASSWORD = 'password';
    public static $ONLY_CODE = 'code';
    public static $PASSWORD_CODE = 'password_code';
    public static $CODE_PASSWORD = 'code_password';
    public static $OPTIONAL_PASSWORD_CODE = 'optional_password_code';
    public static $OPTIONAL_CODE_PASSWORD = 'optional_code_password';

    // -------------------------------------------------------------------------------------
    public static $configOtpServices = 'novel-auth.otp_services';
    public static $otpServices = 'otp_services';
    public static $OTP_EMAIL = 'email';
    public static $OTP_CALL = 'call';
    public static $OTP_SMS = 'sms';
    public static $OTP_USSD = 'ussd';
    public static $OTP_TELEGRAM = 'telegram';
    public static $OTP_WHATSAPP = 'whatsapp';
    public static $OTP_GENERATOR = 'otp_generator';

    public static $configOptLength = 'novel-auth.otp_length';
    public static $optLength = 'otp_length';

    public static $configEmailTTL = 'novel-auth.email_ttl';
    public static $emailTTL = 'email_ttl';

    public static $configCallTTL = 'novel-auth.call_ttl';
    public static $callTTL = 'call_ttl';

    public static $configSmsTTL = 'novel-auth.sms_ttl';
    public static $smsTTL = 'sms_ttl';

    public static $configTelegramTTL = 'novel-auth.telegram_ttl';
    public static $telegramTTL = 'telegram_ttl';

    public static $configWhatsappTTL = 'novel-auth.whatsapp_ttl';
    public static $whatsappTTL = 'whatsapp_ttl';

    // -------------------------------------------------------------------------------------
    public static $configTokenExpiration = 'novel-auth.token_expiration';
    public static $tokenExpiration = 'token_expiration';

    public static $configOtpCodeTtl = 'novel-auth.otp_code_ttl';
    public static $otpCodeTtl = 'otp_code_ttl';

    public static $configEncryptOtpCode = 'novel-auth.encrypt_otp_code';
    public static $encryptOtpCode = 'encrypt_otp_code';

    // -------------------------------------------------------------------------------------
    public static $configPrefix = 'novel-auth.prefix';
    public static $prefix = 'prefix';
    public static $configDomain = 'novel-auth.domain';
    public static $domain = 'domain';

    // -------------------------------------------------------------------------------------
    public static $configHome = 'novel-auth.home';
    public static $home = 'home';

    public static $configViews = 'novel-auth.views';
    public static $views = 'views';

    public static $configMiddleware = 'novel-auth.middleware';
    public static $middleware = 'middleware';

    public static $configGuard = 'novel-auth.guard';
    public static $guard = 'guard';

    public static $configLimiters = 'novel-auth.limiters';
    public static $limiters = 'limiters';

    public static $configLimitersAuth = 'novel-auth.limiters.auth';
    public static $limitersAuth = 'auth';

    public static $configSecretKey = 'novel-auth.secret_key';
    public static $secretKey = 'secret_key';
}
