<?php

namespace App\Actions\NovelAuth;

use Hos3ein\NovelAuth\Contracts\OtpManager as OtpManagerContacts;

class OtpManager implements OtpManagerContacts
{
    public function sendEmail($user, $code)
    {
        // TODO: Implement sendEmail() method.
        return true;
    }

    public function addCallToQueue($user, $code)
    {
        // TODO: Implement addCallToQueue() method.
        return true;
    }

    public function sendSms($user, $code)
    {
        // TODO: Implement sendSms() method.
        return true;
    }

    public function getUssd($user, $code)
    {
        // TODO: Implement getUssd() method.
        return '*6655*1*123456#';
    }

    public function sendTelegram($user, $code)
    {
        // TODO: Implement sendTelegram() method.
        return true;
    }

    public function sendWhatsapp($user, $code)
    {
        // TODO: Implement sendWhatsapp() method.
        return true;
    }
}
