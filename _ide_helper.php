<?php
// @formatter:off

namespace Illuminate\Http {

    use Hos3ein\NovelAuth\Model\HasOtpCodes;
    use Hos3ein\NovelAuth\Model\NovelAuthAuthenticatable;
    use Illuminate\Database\Eloquent\Model;
    use Laravel\Fortify\TwoFactorAuthenticatable;
    use Lcobucci\JWT\Token;

    class Request
    {
        /** @var Model|TwoFactorAuthenticatable|HasOtpCodes|NovelAuthAuthenticatable */
        public $tempUser;

        /** @var Token */
        public $claims;

        /** @var string */
        public $emailPhone;

        /** @var string */
        public $inputType;

        /** @var string */
        public $token_rc;

        /** @var string */
        public $email_phone;

        /** @var string */
        public $code;

        /** @var string */
        public $pass;

        /** @var string */
        public $pass_conf;

        /** @var string */
        public $force_otp_type;
    }
}

namespace Hos3ein\NovelAuth\Model {
    trait HasOtpCodes
    {
        /** @var string */
        public $two_factor_secret;

        /** @var string */
        public $email_verified_at;

        /** @var string */
        public $phone_verified_at;

        /** @var string */
        public $login_force_both;

        /** @var string */
        public $email;

        /** @var string */
        public $phone;
    }

    trait NovelAuthAuthenticatable
    {
        /** @var string */
        public $status;

        /** @var string */
        public $password;
    }
}

