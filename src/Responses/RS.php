<?php

namespace Hos3ein\NovelAuth\Responses;

use Hos3ein\NovelAuth\Contracts\OtpManager;
use Hos3ein\NovelAuth\Features\Constants;
use Hos3ein\NovelAuth\NovelAuth;
use Illuminate\Http\JsonResponse;
use Lcobucci\JWT\Token;

class RS
{
    public static function resJson($nextPage, ?Token $token, $msg, $err = null, $otpOptions = null, $otpType = null, $ttl = null, $canPassword = false, $canOtp = false): JsonResponse
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
        if ($token) $data['token_rc'] = $token->__toString();
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

        auth(config(Constants::$configGuard))->login($request->tempUser, $request->filled('remember'));
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

    public static function go2CodeOptions($token_rc, $message, $otpOptions, $canPassword = false)
    {
        if (request()->wantsJson())
            return self::resJson('code_options', $token_rc, $message, null, $otpOptions, null, null, $canPassword);
        return view(NovelAuth::codeOptionsView(), compact('token_rc', 'message', 'otpOptions', 'canPassword'));
    }

    public static function back2CodeOptions($token_rc, $err, $otpOptions, $canPassword = false)
    {
        $message = __('novel-auth::otp.options');
        if (request()->wantsJson())
            return self::resJson('code_options', $token_rc, $message, $err, $otpOptions, null, null, $canPassword);
        return view(NovelAuth::codeOptionsView(), compact('token_rc', 'message', 'otpOptions', 'canPassword'))->withErrors([$err]);
    }

}
