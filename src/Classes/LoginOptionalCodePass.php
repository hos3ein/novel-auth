<?php

namespace Hos3ein\NovelAuth\Classes;

use Hos3ein\NovelAuth\Features\Constants;
use Hos3ein\NovelAuth\Responses\RS;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Hashing\Hasher as HasherContract;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LoginOptionalCodePass
{
    /**
     * @param Request $request
     * @param $next
     * @return Application|Factory|View|JsonResponse
     */
    public function handle(Request $request, $next)
    {
        if (!$request->tempUser->isCompleteRegistrationUser())
            return $next($request);

        if (in_array(config(Constants::$configLoginMode), [Constants::$OPTIONAL_PASSWORD_CODE, Constants::$OPTIONAL_CODE_PASSWORD])) {
            $otpOptions = $request->tempUser->getAvailableOtpOptionsForUser();

            $pass = $request->pass;
            if ($pass) {
                if (app(HasherContract::class)->check($pass, $request->tempUser->password)) {
                    return RS::go2Home($request);
                } else
                    return RS::back2Password($request->claims, __('novel-auth::messages.login.pass_error'), $otpOptions, !empty($otpOptions));
            }

            $code = $request->code;
            if ($code) {
                $otpType = $request->claims->getClaim('otp_type');
                if ($request->tempUser->verifyCode($otpType, $code)) {
                    $request->tempUser->setVerifyAt($request->claims->getClaim('input_type') == Constants::$EMAIL_MODE ? 'email' : 'phone');
                    $request->tempUser->deleteAllOtpCodes();
                    return RS::go2Home($request);
                } else {
                    $remainingTtl = $request->tempUser->getRemainingTtlFromLastSend($otpType);
                    return RS::back2Code($request->claims, __('novel-auth::messages.otp.error.invalid_code'), $otpOptions, $otpType, $remainingTtl, true);
                }
            }

            $force_otp_type = $request->force_otp_type;
            if ($force_otp_type) {
                if ($force_otp_type == 'password')
                    return RS::go2Password($request->claims, __('novel-auth::messages.login.pass'), $otpOptions, !empty($otpOptions));
                else if ($force_otp_type == 'otp_options') {  // client request otp_options page
                    $request->force_otp_type = null;
                    $request->claims = TM::removeFromClaims($request->claims, 'otp_type');
                }
            } else {
                if (config(Constants::$configLoginMode) == Constants::$OPTIONAL_PASSWORD_CODE) {
                    return RS::go2Password($request->claims, __('novel-auth::messages.login.pass'), $otpOptions, !empty($otpOptions));
                } else {
                    if (empty($otpOptions)) {
                        return RS::go2Password($request->claims, __('novel-auth::messages.login.no_otp_use_pass'), null, false);
                    }
                }
            }
            return Otp::sendOtpLogin($request, $otpOptions, !empty($otpOptions));
        }
        return $next($request);
    }
}
