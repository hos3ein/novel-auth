<?php

namespace Hos3ein\NovelAuth\Contracts;

use Hos3ein\NovelAuth\Model\HasOtpCodes;
use Hos3ein\NovelAuth\Model\NovelAuthAuthenticatable;
use Illuminate\Database\Eloquent\Model;
use Laravel\Fortify\TwoFactorAuthenticatable;

interface OtpManager
{
    /**
     * @param Model|TwoFactorAuthenticatable|HasOtpCodes|NovelAuthAuthenticatable $user
     * @param $code
     * @return mixed
     */
    public function sendEmail($user, $code);

    /**
     * @param Model|TwoFactorAuthenticatable|HasOtpCodes|NovelAuthAuthenticatable $user
     * @param $code
     * @return mixed
     */
    public function addCallToQueue($user, $code);

    /**
     * @param Model|TwoFactorAuthenticatable|HasOtpCodes|NovelAuthAuthenticatable $user
     * @param $code
     * @return mixed
     */
    public function sendSms($user, $code);

    /**
     * @param Model|TwoFactorAuthenticatable|HasOtpCodes|NovelAuthAuthenticatable $user
     * @param $code
     * @return mixed
     */
    public function getUssd($user, $code);

    /**
     * @param Model|TwoFactorAuthenticatable|HasOtpCodes|NovelAuthAuthenticatable $user
     * @param $code
     * @return mixed
     */
    public function sendTelegram($user, $code);

    /**
     * @param Model|TwoFactorAuthenticatable|HasOtpCodes|NovelAuthAuthenticatable $user
     * @param $code
     * @return mixed
     */
    public function sendWhatsapp($user, $code);
}
