<?php

namespace Hos3ein\NovelAuth\Model;

use Hos3ein\NovelAuth\Classes\Otp;
use Hos3ein\NovelAuth\Features\Constants;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Crypt;
use Laravel\Fortify\Contracts\TwoFactorAuthenticationProvider;

trait HasOtpCodes
{
    /**
     * Get the otpCodes that belong to model.
     *
     * @return MorphMany
     */
    public function otpCodes(): MorphMany
    {
        return $this->morphMany(OtpCode::class, 'otpable');
    }

    public function verifyCode($otpType, $code): bool
    {
        if ($otpType == Constants::$OTP_GENERATOR)
            return !is_null($this->two_factor_secret) and $this->verify2FACode($code);

        $otpCode = $this->otpCodes()
            ->where('type', $otpType)
            ->where('updated_at', '>', now()->modify('-' . config(Constants::$configOtpCodeTtl)))
            ->first();
        if ($otpCode) {
            $c = config(Constants::$configEncryptOtpCode) ? Crypt::decryptString($otpCode->code) : $otpCode->code;
            return $code == $c;
        }
        return false;
    }

    public function getRemainingTtlFromLastSend($otpType): int
    {
        if ($otpType == Constants::$OTP_USSD)
            return 0;
        $otpCode = $this->otpCodes()
            ->where('type', $otpType)
            ->first();
        if ($otpCode) {
            if (!is_null($otpCode->code)) {
                $ttl = Otp::getOtpTtl($otpType) - now()->diffInSeconds($otpCode->updated_at);
                return $ttl > 0 ? $ttl : 0;
            }
        }
        return 0;
    }

    public function deleteAllOtpCodes()
    {
        $this->otpCodes()->update(['code' => null]);
    }

    public function setVerifyAt($type)
    {
        if ($type == 'email') {
            if ($this->email_verified_at == null)
                $this->email_verified_at = now();
        } else if ($type == 'phone') {
            if ($this->phone_verified_at == null)
                $this->phone_verified_at = now();
        }
        if ($this->isDirty())
            $this->save();
    }

    public function isUserForceBoth(): bool
    {
        return $this->login_force_both == true;
    }

    public function getAvailableOtpOptionsForUser(): array
    {
        $res = [];
        foreach (config(Constants::$configOtpServices) as $service) {
            if ($service == Constants::$OTP_GENERATOR)
                if ($this->two_factor_secret != null)
                    $res[] = ['type' => $service, 'id' => 'One-time password generator'];
            if ($service == Constants::$OTP_EMAIL)
                if ($this->email_verified_at != null)
                    $res[] = ['type' => $service, 'id' => '***' . $this->email];
            if (in_array($service, [Constants::$OTP_CALL, Constants::$OTP_SMS, Constants::$OTP_USSD]))
                if ($this->phone_verified_at != null)
                    $res[] = ['type' => $service, 'id' => '***' . $this->phone];

            if (in_array($service, [Constants::$OTP_TELEGRAM, Constants::$OTP_WHATSAPP])) {
                $t = $this->otpCodes()->where('type', $service)->first();
                if ($t and $t->payload['active'] == true)
                    $res[] = ['type' => $service, 'id' => '***' . $this->phone];
            }
        }
        return $res;
    }

    public function verify2FACode($code): bool
    {
        if ($c = $this->validRecoveryCode($code)) {
            $this->replaceRecoveryCode($c);
        } elseif (!$this->hasValidCode($code)) {
            return false;
        }
        return true;
    }

    public function validRecoveryCode($inputRecoveryCode)
    {
        if (!$inputRecoveryCode) return null;
        return collect($this->recoveryCodes())->first(function ($code) use ($inputRecoveryCode) {
            return hash_equals($inputRecoveryCode, $code) ? $code : null;
        });
    }

    public function hasValidCode($inputCode): bool
    {
        return $inputCode && app(TwoFactorAuthenticationProvider::class)->verify(
                decrypt($this->two_factor_secret), $inputCode
            );
    }
}
