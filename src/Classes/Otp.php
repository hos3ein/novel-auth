<?php

namespace Hos3ein\NovelAuth\Classes;

use Exception;
use Hos3ein\NovelAuth\Contracts\OtpManager;
use Hos3ein\NovelAuth\Features\Constants;
use Hos3ein\NovelAuth\Model\HasOtpCodes;
use Hos3ein\NovelAuth\Responses\RS;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class Otp
{
    public static function sendOtpRegister(Request $request)
    {
        $emailPhone = $request->claims->getClaim('email_phone');
        $inputType = $request->claims->getClaim('input_type');
        $force_otp_type = $request->force_otp_type;
        $otp_type = $request->claims->getClaim('otp_type', 'null');

        if ($inputType == Constants::$EMAIL_MODE) {
            if (in_array(Constants::$OTP_EMAIL, config(Constants::$configOptServices))) {
                // $remainingTtl = AM::getRemainingTtlFromLastSend($request, Constants::$OTP_EMAIL);
                $remainingTtl = $request->tempUser->getRemainingTtlFromLastSend(Constants::$OTP_EMAIL);
                $request->claims = TM::appendToClaims($request->claims, 'otp_type', Constants::$OTP_EMAIL);
                if ($remainingTtl <= 0) {
                    if (self::sendOtp($request->tempUser, Constants::$OTP_EMAIL)) {
                        return RS::go2Code($request->claims,
                            __('novel-auth::messages.otp.send.email', ['identifier' => $emailPhone]),
                            [['type' => Constants::$OTP_EMAIL, 'id' => $emailPhone]],
                            Constants::$OTP_EMAIL, config(Constants::$configEmailTTL));
                    } else {
                        return RS::back2Auth(__('novel-auth::messages.otp.error.email', ['identifier' => $emailPhone]));
                    }
                } else {
                    return RS::go2Code($request->claims,
                        __('novel-auth::messages.otp.sent.email', ['identifier' => $emailPhone]),
                        [['type' => Constants::$OTP_EMAIL, 'id' => $emailPhone]],
                        Constants::$OTP_EMAIL, $remainingTtl);
                }
            } else {
                return self::checkOtherMethod($inputType);
            }
        } else { // phone
            if (empty(config(Constants::$configRegisterPhoneOptServices))) {
                return self::checkOtherMethod($inputType);
            }
            if ($force_otp_type == 'otp_options') {  // client request otp_options page
                $request->force_otp_type = null;
                $force_otp_type = null;
                $otp_type = 'null';
                $request->claims = TM::removeFromClaims($request->claims, 'otp_type');
            }
            if ($force_otp_type) {
                return self::sendOtp2Phone($request, $force_otp_type);
            } else {
                if ($otp_type != 'null') {
                    return self::sendOtp2Phone($request, $otp_type);
                } else {
                    $df = config(Constants::$configDefaultRegisterPhoneOptServices);
                    if (is_null($df)) {
                        return RS::go2CodeOptions($request->claims,
                            __('novel-auth::messages.otp.options'),
                            self::getRegisterPhoneOptServices($emailPhone));
                    } else {
                        return self::sendOtp2Phone($request, $df);
                    }
                }
            }
        }
    }

    public static function sendOtp2Phone(Request $request, $type)
    {
        if (in_array($type, config(Constants::$configRegisterPhoneOptServices))) {
            $emailPhone = $request->claims->getClaim('email_phone');
            $remainingTtl = $request->tempUser->getRemainingTtlFromLastSend($type);
            $request->claims = TM::appendToClaims($request->claims, 'otp_type', $type);
            if ($remainingTtl <= 0) {
                if ($sentCode = self::sendOtp($request->tempUser, $type)) {
                    $message = ($type == Constants::$OTP_USSD)
                        ? __('novel-auth::messages.otp.send.ussd', ['identifier' => $emailPhone, 'ussd' => $sentCode])
                        : __('novel-auth::messages.otp.send.' . $type, ['identifier' => $emailPhone]);
                    return RS::go2Code($request->claims, $message, self::getRegisterPhoneOptServices($emailPhone), $type, self::getOtpTtl($type));
                } else {
                    if (count(config(Constants::$configRegisterPhoneOptServices)) == 1)
                        return RS::back2Auth(__('novel-auth::messages.otp.error.' . $type, ['identifier' => $emailPhone]));
                    else
                        return RS::back2CodeOptions($request->claims,
                            __('novel-auth::messages.otp.error.' . $type, ['identifier' => $emailPhone]),
                            self::getRegisterPhoneOptServices($emailPhone));
                }
            } else {
                return RS::go2Code($request->claims,
                    __('novel-auth::messages.otp.sent.' . $type, ['identifier' => $emailPhone]),
                    self::getRegisterPhoneOptServices($emailPhone), $type, $remainingTtl);
            }
        } else {
            return RS::back2Auth(__('novel-auth::messages.otp.error.invalid_type'));
        }
    }

    public static function getRegisterPhoneOptServices($phone): array
    {
        $res = [];
        foreach (config(Constants::$configRegisterPhoneOptServices) as $service) {
            $res[] = ['type' => $service, 'id' => $phone];
        }
        return $res;
    }

    public static function getOtpTtl($type)
    {
        if ($type == Constants::$OTP_EMAIL)
            return config(Constants::$configEmailTTL);
        if ($type == Constants::$OTP_CALL)
            return config(Constants::$configCallTTL);
        if ($type == Constants::$OTP_SMS)
            return config(Constants::$configSmsTTL);
        if ($type == Constants::$OTP_TELEGRAM)
            return config(Constants::$configTelegramTTL);
        if ($type == Constants::$OTP_WHATSAPP)
            return config(Constants::$configWhatsappTTL);
        return 0;
    }

    public static function checkOtherMethod($inputType)
    {
        if ($inputType == Constants::$EMAIL_MODE) {
            if (in_array(Constants::$PHONE_MODE, config(Constants::$configRegisterMethods)) and
                !empty(config(Constants::$configRegisterPhoneOptServices)))
                return RS::back2Auth(__('novel-auth::messages.register.no_otp_email_use_phone'));
            else
                return RS::back2Auth(__('novel-auth::messages.register.no_otp_email_phone'));
        } else {
            if (in_array(Constants::$EMAIL_MODE, config(Constants::$configRegisterMethods)) and
                in_array(Constants::$OTP_EMAIL, config(Constants::$configOptServices)))
                return RS::back2Auth(__('novel-auth::messages.register.no_otp_phone_use_email'));
            else
                return RS::back2Auth(__('novel-auth::messages.register.no_otp_phone_email'));
        }
    }

    /**
     * @param Model|HasOtpCodes $user
     * @param $type
     * @return mixed
     * @throws Exception
     */
    public static function sendOtp($user, $type)
    {
        $code = random_int(10, 99);
        $status = false;

        $otpManager = app(OtpManager::class);

        if ($type == Constants::$OTP_USSD) {
            $status = $otpManager->getUssd($user, $code);
        }
        if ($type == Constants::$OTP_EMAIL) {
            $status = $otpManager->sendEmail($user, $code);
        }
        if ($type == Constants::$OTP_CALL) {
            $status = $otpManager->addCallToQueue($user, $code);
        }
        if ($type == Constants::$OTP_SMS) {
            $status = $otpManager->sendSms($user, $code);
        }
        if ($type == Constants::$OTP_TELEGRAM) {
            $t = $user->otpCodes()->where('type', $type)->first();
            if ($t and $t->payload['active'] == true) {
                $status = $otpManager->sendTelegram($user, $code);
            } else
                $status = false;
        }
        if ($type == Constants::$OTP_WHATSAPP) {
            $t = $user->otpCodes()->where('type', $type)->first();
            if ($t and $t->payload['active'] == true) {
                $status = $otpManager->sendWhatsapp($user, $code);
            } else
                $status = false;
        }
        if ($status)
            $user->otpCodes()->updateOrCreate(['type' => $type], [
                'code' => config(Constants::$configEncryptOtpCode)
                    ? Crypt::encryptString($code)
                    : $code
            ]);
        return $status;
    }

    public static function sendOtpLogin(Request $request, $otpOptions, $canPassword = false)
    {
        // TODO: send otp options when code not present even otp_type exists in claims
        // $otpOptions = AM::getAvailableOtpOptionsForUser($request);
        if (empty($otpOptions)) {
            return RS::back2Auth(__('novel-auth::messages.login.user_no_otp'));
        }
        $force_otp_type = $request->force_otp_type;
        if ($force_otp_type == 'otp_options') {  // client request otp_options page
            $request->force_otp_type = null;
            $request->claims = TM::removeFromClaims($request->claims, 'otp_type');
        }
        if ($force_otp_type) {
            $found_key = array_search($force_otp_type, array_column($otpOptions, 'type'));
            if ($found_key !== false) {
                return self::sendCertainOtp($request, $otpOptions, $force_otp_type, $canPassword);
            } else {
                return RS::back2Auth(__('novel-auth::messages.otp.error.invalid_type'));
            }
        } else {
            if (count($otpOptions) == 1) {
                $otpType = $otpOptions[0]['type'];
                return self::sendCertainOtp($request, $otpOptions, $otpType, $canPassword);
            } else {
                return RS::go2CodeOptions($request->claims, __('novel-auth::messages.otp.options'), $otpOptions, $canPassword);
            }
        }
    }

    public static function sendCertainOtp(Request $request, $otpOptions, $type, $canPassword)
    {
        $request->claims = TM::appendToClaims($request->claims, 'otp_type', $type);
        if ($type == Constants::$OTP_GENERATOR) {
            return RS::go2Code($request->claims, __('novel-auth::messages.otp.send.' . $type), $otpOptions, $type, 0, $canPassword);
        }
        // $emailPhone = $request->claims->getClaim('email_phone');
        // $idType = $type == Constants::$OTP_EMAIL ? 'email' : 'phone';
        $found_key = array_search($type, array_column($otpOptions, 'type'));
        if ($found_key !== false) {
            $identifier = $otpOptions[$found_key]['id'];
        } else return RS::back2Auth('Unknown error');

        $remainingTtl = $request->tempUser->getRemainingTtlFromLastSend($type);
        if ($remainingTtl <= 0) {
            if ($sentCode = self::sendOtp($request->tempUser, $type)) {
                $message = ($type == Constants::$OTP_USSD)
                    ? __('novel-auth::messages.otp.send.ussd', ['identifier' => $identifier, 'ussd' => $sentCode])
                    : __('novel-auth::messages.otp.send.' . $type, ['identifier' => $identifier]);
                return RS::go2Code($request->claims, $message, $otpOptions, $type, self::getOtpTtl($type), $canPassword);
            } else {
                if (count($otpOptions) == 1) {
                    $msg = __('novel-auth::messages.otp.error.' . $type, ['identifier' => $identifier]);
                    if ($canPassword)
                        return RS::back2Password($request->claims, $msg . ' . ' . __('novel-auth::messages.login.one_otp_error_use_pass'), $otpOptions, !empty($otpOptions));
                    return RS::back2Auth($msg);
                } else {
                    return RS::back2CodeOptions($request->claims,
                        __('novel-auth::messages.otp.error.' . $type, ['identifier' => $identifier]),
                        $otpOptions, $canPassword);
                }
            }
        } else {
            return RS::go2Code($request->claims,
                __('novel-auth::messages.otp.sent.' . $type, ['identifier' => $identifier]),
                $otpOptions, $type, $remainingTtl, $canPassword);
        }
    }
}
