<?php

namespace Hos3ein\NovelAuth\Contracts;

use Hos3ein\NovelAuth\Model\HasOtpCodes;
use Hos3ein\NovelAuth\Model\NovelAuthAuthenticatable;
use Illuminate\Database\Eloquent\Model;
use Laravel\Fortify\TwoFactorAuthenticatable;

interface AccountManager
{
    /**
     * @param $model
     * @param $emailPhone
     * @param $inputType
     * @return Model|HasOtpCodes|TwoFactorAuthenticatable|NovelAuthAuthenticatable
     */
    public function findOrCreateIncompleteRegistrationUser($model, $emailPhone, $inputType);
    // TODO: get model from provider
}
