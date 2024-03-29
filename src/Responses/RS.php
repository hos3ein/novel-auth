<?php

namespace Hos3ein\NovelAuth\Responses;

use Hos3ein\NovelAuth\Contracts\OtpManager;
use Hos3ein\NovelAuth\Features\Constants;
use Hos3ein\NovelAuth\NovelAuth;
use Illuminate\Http\JsonResponse;
use Lcobucci\JWT\Token\Plain;

class RS
{
    public static function resJson($nextPage, ?Plain $token, $msg, $err = null, $otpOptions = null, $otpType = null, $ttl = null, $canPassword = false, $canOtp = false): JsonResponse
    {
        $data = [
            'next_page' => $nextPage,
        ];
        if ($err) $data['error'] = $err;
        if ($msg) $data['message'] = $msg;
        if ($otpType) $data['otp_type'] = $otpType;
        if ($ttl) $data['ttl'] = $ttl;
        if ($otpOptions) $data['otp_options'] = $otpOptions;
        if ($canPassword) $data['can_password'] = true;
        if ($canOtp) $data['can_otp'] = true;
        // if ($token) $data['token2'] = $token->getClaims();
        if ($token) $data['token_rc'] = $token->toString();
        return response()->json($data);
    }

    public static function back2Auth($err)
    {
        $message = __('novel-auth::messages.auth');
        if (request()->wantsJson())
            return self::resJson('auth', null, $message, $err);
        return view(NovelAuth::authView(), compact('message'))->withErrors([$err]);
    }

    public static function go2Home($request)
    {
        if (NovelAuth::$onAuthDoneCallback)
            return call_user_func(NovelAuth::$onAuthDoneCallback, $request, $request->tempUser);

        $remember = $request->plain_token->claims()->get('remember', false);
        auth(config(Constants::$configGuard))->login($request->tempUser, $remember);
        return response()->redirectTo(config(Constants::$configHome));
    }

    public static function go2Passwords($token_rc, $message)
    {
        if (request()->wantsJson())
            return self::resJson('passwords', $token_rc, $message);
        return view(NovelAuth::passesView(), compact('token_rc', 'message'));
    }

    public static function back2Passwords($token_rc, $err)
    {
        $message = __('novel-auth::messages.register.passes');
        if (request()->wantsJson())
            return self::resJson('passwords', $token_rc, $message, $err);
        return view(NovelAuth::passesView(), compact('token_rc', 'message'))->withErrors([$err]);
    }

    public static function go2Password($token_rc, $message, $otpOptions = [], $canOtp = false)
    {
        if (request()->wantsJson())
            return self::resJson('password', $token_rc, $message, null, $canOtp ? $otpOptions : null, null, null, false, $canOtp);
        return view(NovelAuth::passView(), compact('token_rc', 'message', 'otpOptions', 'canOtp'));
    }

    public static function back2Password($token_rc, $err, $otpOptions = [], $canOtp = false)
    {
        $message = __('novel-auth::messages.login.pass');
        if (request()->wantsJson())
            return self::resJson('password', $token_rc, $message, $err, $canOtp ? $otpOptions : null, null, null, false, $canOtp);
        return view(NovelAuth::passView(), compact('token_rc', 'message', 'otpOptions', 'canOtp'))->withErrors([$err]);
    }

    public static function go2Code($token_rc, $message, $otpOptions, $otpType, $ttl, $canPassword = false)
    {
        if (request()->wantsJson())
            return self::resJson('code', $token_rc, $message, null, $otpOptions, $otpType, $ttl, $canPassword);
        return view(NovelAuth::codeView(), compact('token_rc', 'message', 'otpOptions', 'otpType', 'ttl', 'canPassword'));
    }

    public static function back2Code($token_rc, $err, $otpOptions, $otpType, $ttl, $canPassword = false)
    {
        $rep = [];
        if ($otpType == Constants::$OTP_USSD) {
            $rep['ussd'] = app(OtpManager::class)->getUssd(null, null); // TODO
        }
        if ($otpType != Constants::$OTP_GENERATOR) {
            $found_key = array_search($otpType, array_column($otpOptions, 'type'));
            $rep['identifier'] = $otpOptions[$found_key]['id'];
        }
        $message = __('novel-auth::messages.otp.send.' . $otpType, $rep);
        if (request()->wantsJson())
            return self::resJson('code', $token_rc, $message, $err, $otpOptions, $otpType, $ttl, $canPassword);
        return view(NovelAuth::codeView(), compact('token_rc', 'message', 'otpOptions', 'otpType', 'ttl', 'canPassword'))->withErrors([$err]);
    }

    public static function go2OtpOptions($token_rc, $message, $otpOptions, $canPassword = false)
    {
        if (request()->wantsJson())
            return self::resJson('otp_options', $token_rc, $message, null, $otpOptions, null, null, $canPassword);
        return view(NovelAuth::otpOptionsView(), compact('token_rc', 'message', 'otpOptions', 'canPassword'));
    }

    public static function back2OtpOptions($token_rc, $err, $otpOptions, $canPassword = false)
    {
        $message = __('novel-auth::otp.options');
        if (request()->wantsJson())
            return self::resJson('otp_options', $token_rc, $message, $err, $otpOptions, null, null, $canPassword);
        return view(NovelAuth::otpOptionsView(), compact('token_rc', 'message', 'otpOptions', 'canPassword'))->withErrors([$err]);
    }

    public static function otpLabel($otpType, $otpId)
    {
        switch ($otpType){
            case Constants::$OTP_EMAIL:
                return "ارسال ایمیل به $otpId";
            case Constants::$OTP_CALL:
                return "تماس با شماره $otpId";
            case Constants::$OTP_SMS:
                return "ارسال پیامک به $otpId";
            case Constants::$OTP_USSD:
                return "شماره گیری کد ussd با شماره $otpId";
            case Constants::$OTP_TELEGRAM:
                return "ارسال پیام در Telegram به شماره $otpId";
            case Constants::$OTP_WHATSAPP:
                return "ارسال پیام در Whatsapp به شماره $otpId";
            case Constants::$OTP_GENERATOR:
                return "$otpType ($otpId)";
            default:
                return '';
        }
    }
}
