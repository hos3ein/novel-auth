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

class LoginCodePassword
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

        $pass = $request->pass;
        $code = $request->code;

        if (in_array(config(Constants::$configLoginMode), [Constants::$ONLY_CODE, Constants::$CODE_PASSWORD])
            or (config(Constants::$configLoginMode) == Constants::$OPTIONAL_CODE_PASSWORD and $request->tempUser->isUserForceBoth())) {
            if (in_array(config(Constants::$configLoginMode), [Constants::$CODE_PASSWORD, Constants::$OPTIONAL_CODE_PASSWORD])) {
                if ($request->plain_token->claims()->get('verified', false)) {
                    if ($pass) {
                        if (app(HasherContract::class)->check($pass, $request->tempUser->password)) {
                            return RS::go2Home($request);
                        } else
                            return RS::back2Password($request->plain_token, __('novel-auth::messages.login.pass_error'));
                    } else
                        return RS::go2Password($request->plain_token, __('novel-auth::messages.login.pass'));
                }
            }
            if ($code) {
                $otpType = $request->plain_token->claims()->get('otp_type');
                if ($request->tempUser->verifyCode($otpType, $request->code)) {
                    $request->tempUser->setVerifyAt($request->plain_token->claims()->get('input_type') == Constants::$EMAIL_MODE ? 'email' : 'phone');
                    $request->tempUser->deleteAllOtpCodes();
                    if (config(Constants::$configLoginMode) == Constants::$ONLY_CODE) {
                        return RS::go2Home($request);
                    } else {
                        $request->plain_token = TM::appendToClaims($request->plain_token, 'verified', true);
                        $request->plain_token = TM::removeFromClaims($request->plain_token, 'otp_type');
                        return RS::go2Password($request->plain_token, __('novel-auth::messages.login.pass'));
                    }
                } else {
                    $otpOptions = $request->tempUser->getAvailableOtpOptionsForUser();
                    $remainingTtl = $request->tempUser->getRemainingTtlFromLastSend($otpType);
                    return RS::back2Code($request->plain_token, __('novel-auth::messages.otp.error.invalid_code'), $otpOptions, $otpType, $remainingTtl);
                }
            }
            return Otp::sendOtpLogin($request, $request->tempUser->getAvailableOtpOptionsForUser());
        }
        return $next($request);
    }
}
