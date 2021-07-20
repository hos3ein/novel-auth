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

class LoginPasswordCode
{
    /**
     * @param Request $request
     * @param $next
     * @return Application|Factory|View|JsonResponse|mixed
     */
    public function handle(Request $request, $next)
    {
        if (!$request->tempUser->isCompleteRegistrationUser())
            return $next($request);

        if (in_array(config(Constants::$configLoginMode), [Constants::$ONLY_PASSWORD, Constants::$PASSWORD_CODE])
            or (config(Constants::$configLoginMode) == Constants::$OPTIONAL_PASSWORD_CODE and $request->tempUser->isUserForceBoth())) {
            if (in_array(config(Constants::$configLoginMode), [Constants::$PASSWORD_CODE, Constants::$OPTIONAL_PASSWORD_CODE])) {
                if ($request->claims->getClaim('verified', false)) {
                    $otpType = $request->claims->getClaim('otp_type');
                    if ($request->code) {
                        if ($request->tempUser->verifyCode($otpType, $request->code)) {
                            $request->tempUser->setVerifyAt($request->claims->getClaim('input_type') == Constants::$EMAIL_MODE ? 'email' : 'phone');
                            $request->tempUser->deleteAllOtpCodes();
                            return RS::go2Home($request);
                        } else {
                            $otpOptions = $request->tempUser->getAvailableOtpOptionsForUser();
                            $remainingTtl = $request->tempUser->getRemainingTtlFromLastSend($otpType);
                            return RS::back2Code($request->claims,
                                __('novel-auth::messages.otp.error.invalid_code')
                                , $otpOptions, $otpType, $remainingTtl);
                        }
                    } else
                        return Otp::sendOtpLogin($request, $request->tempUser->getAvailableOtpOptionsForUser());
                }
            }
            $pass = $request->pass;
            if ($pass) {
                if (app(HasherContract::class)->check($pass, $request->tempUser->password)) {
                    if (config(Constants::$configLoginMode) == Constants::$ONLY_PASSWORD) {
                        return RS::go2Home($request);
                    } else {
                        $request->claims = TM::appendToClaims($request->claims, 'verified', true);
                        return Otp::sendOtpLogin($request, $request->tempUser->getAvailableOtpOptionsForUser());
                    }
                } else
                    return RS::back2Password($request->claims, __('novel-auth::messages.login.pass_error'));
            }
            return RS::go2Password($request->claims, __('novel-auth::messages.login.pass'));
        }
        return $next($request);
    }
}
