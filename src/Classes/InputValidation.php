<?php

namespace Hos3ein\NovelAuth\Classes;

use DateTime;
use DateTimeImmutable;
use Hos3ein\NovelAuth\Features\Constants;
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
            $request->claims = TM::ParseToken($request->token_rc);
            if (TM::validToken($request->claims)) {

                // expiration check
                // if (!$request->claims->hasClaim('exp') or $request->claims->isExpired())
                if (!$request->claims->hasClaim('iat') or
                    (new DateTime()) > (new DateTimeImmutable())->setTimestamp($request->claims->getClaim('iat'))->modify(config(Constants::$configTokenExpiration)))
                    return RS::back2Auth(__('novel-auth::messages.token.expired'));

                // blacklist check
                $jwtId = $request->claims->getClaim('jti');
                if (Cache::get($jwtId, false))
                    return RS::back2Auth(__('novel-auth::messages.token.consumed'));
                Cache::forever($jwtId, 'forever');
                $request->claims = TM::appendToClaims($request->claims, 'jti', Str::random());

                // update issue_at
                $request->claims = TM::appendToClaims($request->claims, 'iat', (new DateTime())->getTimestamp());

                return $next($request);
            } else {
                return RS::back2Auth(__('novel-auth::messages.token.invalid'));
            }
        } else {
            $emailPhone = $request->email_phone;
            list($inputValid, $inputType) = self::emailPhoneValidation($emailPhone);
            if ($inputValid) {
                $request->inputType = $inputType;
                $request->emailPhone = $emailPhone;
                $request->claims = TM::createAuthProcessToken($request);
                return $next($request);
            }
        }

        throw ValidationException::withMessages(
            [__('novel-auth::messages.email_phone.invalid.' . ($inputType == Constants::$EMAIL_MODE ? 'email' : 'phone'))]
        );
    }

    public static function emailPhoneValidation($emailPhone): array
    {
        if (is_numeric($emailPhone))
            return array(Str::length($emailPhone) > 0 and Str::length($emailPhone) < 10, Constants::$PHONE_MODE);
        else
            return array(filter_var($emailPhone, FILTER_VALIDATE_EMAIL), Constants::$EMAIL_MODE);
    }
}
