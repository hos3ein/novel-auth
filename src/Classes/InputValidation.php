<?php

namespace Hos3ein\NovelAuth\Classes;

use DateTime;
use DateTimeImmutable;
use Hos3ein\NovelAuth\Features\Constants;
use Hos3ein\NovelAuth\NovelAuth;
use Hos3ein\NovelAuth\Responses\RS;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class InputValidation
{

    /**
     * @param Request $request
     * @param $next
     * @return Application|Factory|View|JsonResponse|mixed
     * @throws ValidationException
     */
    public function handle(Request $request, $next)
    {
        if ($request->has('email_phone')) {
            foreach (['token_rc', 'pass', 'pass_conf', 'code', 'force_otp_type'] as $param)
                if ($request->has($param))
                    throw ValidationException::withMessages(["The $param field is prohibited when email_phone is presented."]);
        } else if (!$request->has('token_rc')) {
            throw ValidationException::withMessages(['The email_phone field is required.']);
        }

        if ($request->token_rc) {
            $request->plain_token = TM::ParseToken($request->token_rc);
            if (TM::validToken($request->plain_token)) {

                // expiration check
                if (!$request->plain_token->claims()->has('iat') or
                    (new DateTime()) > (new DateTimeImmutable())->setTimestamp($request->plain_token->claims()->get('iat'))->modify(config(Constants::$configTokenExpiration)))
                    return RS::back2Auth(__('novel-auth::messages.token.expired'));

                // blacklist check
                $jwtId = $request->plain_token->claims()->get('jti');
                if (Cache::get($jwtId, false))
                    return RS::back2Auth(__('novel-auth::messages.token.consumed'));
                Cache::forever($jwtId, 'forever');
                $request->plain_token = TM::appendToClaims($request->plain_token, 'jti', Str::random());

                // update issue_at
                $request->plain_token = TM::appendToClaims($request->plain_token, 'iat', (new DateTime())->getTimestamp());

                return $next($request);
            } else {
                return RS::back2Auth(__('novel-auth::messages.token.invalid'));
            }
        } else {
            $emailPhone = $request->email_phone;
            list($inputValid, $inputType) = call_user_func(NovelAuth::emailPhoneValidationCallback(), $emailPhone);
            if ($inputValid) {
                $request->inputType = $inputType;
                $request->emailPhone = $emailPhone;
                $request->plain_token = TM::createAuthProcessToken($request);
                return $next($request);
            }
        }

        return RS::back2Auth(__('novel-auth::messages.email_phone.invalid.' . ($inputType == Constants::$EMAIL_MODE ? 'email' : 'phone')));
    }
}
