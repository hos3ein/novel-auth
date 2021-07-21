<?php

namespace Hos3ein\NovelAuth\Classes;

use Hos3ein\NovelAuth\Features\Constants;
use Hos3ein\NovelAuth\NovelAuth;
use Hos3ein\NovelAuth\Responses\RS;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegisterOnlyPassword
{
    /**
     * @param Request $request
     * @param $next
     * @return false|Application|Factory|View|JsonResponse|RedirectResponse|mixed
     */
    public function handle(Request $request, $next)
    {
        if ($request->tempUser->isCompleteRegistrationUser()) {
            return $next($request);
        }

        if (config(Constants::$configRegisterMode) == Constants::$CP_ONLY_PASSWORD) {
            $pass1 = $request->pass;
            $pass2 = $request->pass_conf;

            if ($pass1) {
                $validator = Validator::make(['pass' => $pass1], ['pass' => NovelAuth::passValidationRule()]);
                if ($validator->fails())
                    return RS::back2Passwords($request->claims, $validator->errors()->messages()['pass'][0]);

                if ($pass1 == $pass2) {
                    $request->tempUser->setCompleteRegistrationUser($pass1);
                    return RS::go2Home($request);
                } else
                    return RS::back2Passwords($request->claims, __('novel-auth::messages.register.pass_conf'));
            }
            return RS::go2Passwords($request->claims, __('novel-auth::messages.register.passes'));
        }
        return $next($request);
    }
}
