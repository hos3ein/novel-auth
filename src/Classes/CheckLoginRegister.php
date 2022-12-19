<?php

namespace Hos3ein\NovelAuth\Classes;

use Hos3ein\NovelAuth\Contracts\AccountManager;
use Hos3ein\NovelAuth\Features\Constants;
use Hos3ein\NovelAuth\Responses\RS;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CheckLoginRegister
{
    /**
     * @param Request $request
     * @param $next
     * @return Application|Factory|View|JsonResponse|mixed
     */
    public function handle(Request $request, $next)
    {
        $request->tempUser = app(AccountManager::class)->findOrCreateIncompleteRegistrationUser(
            auth(config(Constants::$configGuard))->getProvider()->getModel(),
            $request->plain_token->claims()->get('email_phone'),
            $request->plain_token->claims()->get('input_type')
        );

        if ($request->tempUser->isCompleteRegistrationUser()) {
            if (is_null(config(Constants::$configLoginMode)))
                return RS::back2Auth(__('novel-auth::messages.login.disabled'));
            if (!$request->tempUser->isActive())
                return RS::back2Auth($request->tempUser->inActiveMessage());
        } else {
            if (empty(config(Constants::$configRegisterMethods)))
                return RS::back2Auth(__('novel-auth::messages.register.disabled'));
            if (!in_array($request->plain_token->claims()->get('input_type'), config(Constants::$configRegisterMethods)))
                return RS::back2Auth(__('novel-auth::messages.register.with_' . ($request->plain_token->claims()->get('input_type') == Constants::$EMAIL_MODE ? 'phone' : 'email')));
        }
        return $next($request);
    }

}
