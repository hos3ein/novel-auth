<?php

namespace Hos3ein\NovelAuth\Classes;

use Hos3ein\NovelAuth\Features\Constants;
use Hos3ein\NovelAuth\NovelAuth;
use Hos3ein\NovelAuth\Responses\RS;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RegisterCodePassword
{
    /**
     * @param Request $request
     * @param $next
     * @return Application|Factory|View|JsonResponse
     */
    public function handle(Request $request, $next)
    {
        if ($request->tempUser->isCompleteRegistrationUser()) {
            return $next($request);
        }

        $code = $request->code;
        $pass1 = $request->pass;
        $pass2 = $request->pass_conf;

        if (config(Constants::$configRegisterMode) != Constants::$CP_ONLY_PASSWORD) {
            if (config(Constants::$configRegisterMode) == Constants::$CP_CODE_PASSWORD) {
                if ($request->claims->getClaim('verified', false)) {
                    $validator = Validator::make(['pass' => $pass1], ['pass' => NovelAuth::passValidationRule()]);
                    if ($validator->fails())
                        return RS::back2Passwords($request->claims, $validator->errors()->messages()['pass'][0]);
                    if ($pass1 and $pass1 == $pass2) {
                        $request->tempUser->setCompleteRegistrationUser($pass1);
                        return RS::go2Home($request);
                    } else
                        return RS::back2Passwords($request->claims, __('novel-auth::messages.register.pass_conf'));
                }
            }
            if ($code) {
                $otpType = $request->claims->getClaim('otp_type'); // it is normal to throw because token is invalid
                if ($request->tempUser->verifyCode($otpType, $code)) {
                    $request->tempUser->setVerifyAt($request->claims->getClaim('input_type') == Constants::$EMAIL_MODE ? 'email' : 'phone');
                    $request->tempUser->deleteAllOtpCodes();
                    if (config(Constants::$configRegisterMode) == Constants::$CP_ONLY_CODE) {
                        $request->tempUser->setCompleteRegistrationUser(Str::random());
                        return RS::go2Home($request);
                    } else {
                        $request->claims = TM::appendToClaims($request->claims, 'verified', true);
                        $request->claims = TM::removeFromClaims($request->claims, 'otp_type');
                        return RS::go2Passwords($request->claims, __('novel-auth::messages.register.passes'));
                    }
                } else {
                    $inputType = $request->claims->getClaim('input_type');
                    $emailPhone = $request->claims->getClaim('email_phone');
                    $otpOptions = $inputType == Constants::$EMAIL_MODE
                        ? [['type' => Constants::$OTP_EMAIL, 'id' => $emailPhone]]
                        : Otp::getRegisterPhoneOptServices($emailPhone);
                    $remainingTtl = $request->tempUser->getRemainingTtlFromLastSend($otpType);
                    return RS::back2Code($request->claims, __('novel-auth::messages.otp.error.invalid_code'), $otpOptions, $otpType, $remainingTtl);
                }
            }
            return Otp::sendOtpRegister($request);
        }
        return $next($request);
    }

}
