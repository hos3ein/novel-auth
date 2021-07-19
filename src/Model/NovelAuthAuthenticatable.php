<?php

namespace Hos3ein\NovelAuth\Model;

use Hos3ein\NovelAuth\Features\Constants;
use Illuminate\Contracts\Hashing\Hasher as HasherContract;

trait NovelAuthAuthenticatable
{
    public function isActive(): bool
    {
        return $this->status == Constants::$STATUS_ACTIVE;
    }

    public function inActiveMessage()
    {
        return __('novel-auth::messages.users.inactive');
    }

    public function isCompleteRegistrationUser(): bool
    {
        return $this->status > Constants::$STATUS_INCOMPLETE_REGISTRATION;
    }

    public function setCompleteRegistrationUser($password)
    {
        $this->status = Constants::$STATUS_ACTIVE;
        $this->password = app(HasherContract::class)->make($password);
        $this->save();
    }
}
