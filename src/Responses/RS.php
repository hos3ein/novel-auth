<?php

namespace Hos3ein\NovelAuth\Responses;

use Hos3ein\NovelAuth\NovelAuth;
use Illuminate\Http\JsonResponse;
use Lcobucci\JWT\Token;

class RS
{
    public static function resJson($nextPage, Token $token, $msg, $err = null, $otpOptions = null, $otpType = null, $ttl = null, $canPassword = false, $canOtp = false): JsonResponse
    {
        $data = [
            'next_page' => $nextPage,
        ];
        // if ($token) $data['token2'] = $token->getClaims();
        if ($token) $data['token_rc'] = $token->__toString();
        if ($msg) $data['message'] = $msg;
        if ($err) $data['error'] = $err;
        if ($otpOptions) $data['otp_options'] = $otpOptions;
        if ($otpType) $data['otp_type'] = $otpType;
        if ($ttl) $data['ttl'] = $ttl;
        if ($canPassword) $data['can_password'] = true;
        if ($canOtp) $data['can_otp'] = true;
        return response()->json($data);
    }

    public static function back2Auth($err)
    {
        if (request()->wantsJson())
            return self::resJson('auth', null, null, $err);
        return view(NovelAuth::authView())->withErrors([$err]);
    }

    public static function go2Home($request)
    {
        if (NovelAuth::$onAuthDoneCallback) {
            return call_user_func(NovelAuth::$onAuthDoneCallback, $request, $request->tempUser);
        }

        $user = $request->tempUser;
        auth()->login($user, $request->filled('remember'));
        return response()->redirectToRoute('auth.profile');
    }

    public static function go2Passwords($token_rc, $message)
    {
        if (request()->wantsJson())
            return self::resJson('passwords', $token_rc, $message);
        return view(NovelAuth::passesView(), compact('token_rc', 'message'));
    }

    public static function back2Passwords($token_rc, $err)
    {
        if (request()->wantsJson())
            return self::resJson('passwords', $token_rc, null, $err);
        return view(NovelAuth::passesView(), compact('token_rc'))->withErrors([$err]);
    }

    public static function go2Password($token_rc, $message, $otpOptions = [], $canOtp = false)
    {
        if (request()->wantsJson())
            return self::resJson('password', $token_rc, $message, null, $canOtp ? $otpOptions : null, null, null, false, $canOtp);
        return view(NovelAuth::passView(), compact('token_rc', 'message', 'otpOptions', 'canOtp'));
    }

    public static function back2Password($token_rc, $err, $otpOptions = [], $canOtp = false)
    {
        if (request()->wantsJson())
            return self::resJson('password', $token_rc, null, $err, $canOtp ? $otpOptions : null, null, null, false, $canOtp);
        return view(NovelAuth::passView(), compact('token_rc', 'otpOptions', 'canOtp'))->withErrors([$err]);
    }

    public static function go2Code($token_rc, $message, $otpOptions, $otpType, $ttl, $canPassword = false)
    {
        if (request()->wantsJson())
            return self::resJson('code', $token_rc, $message, null, $otpOptions, $otpType, $ttl, $canPassword);
        return view(NovelAuth::codeView(), compact('token_rc', 'message', 'otpOptions', 'otpType', 'ttl', 'canPassword'));
    }

    public static function back2Code($token_rc, $err, $otpOptions, $otpType, $ttl, $canPassword = false)
    {
        if (request()->wantsJson())
            return self::resJson('code', $token_rc, null, $err, $otpOptions, $otpType, $ttl, $canPassword);
        return view(NovelAuth::codeView(), compact('token_rc', 'otpOptions', 'otpType', 'ttl', 'canPassword'))->withErrors([$err]);
    }

    public static function go2CodeOptions($token_rc, $message, $otpOptions, $canPassword = false)
    {
        if (request()->wantsJson())
            return self::resJson('code_options', $token_rc, $message, null, $otpOptions, null, null, $canPassword);
        return view(NovelAuth::codeOptionsView(), compact('token_rc', 'message', 'otpOptions', 'canPassword'));
    }

    public static function back2CodeOptions($token_rc, $err, $otpOptions, $canPassword = false)
    {
        if (request()->wantsJson())
            return self::resJson('code_options', $token_rc, null, $err, $otpOptions, null, null, $canPassword);
        return view(NovelAuth::codeOptionsView(), compact('token_rc', 'otpOptions', 'canPassword'))->withErrors([$err]);
    }

}
