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
        $accountManager = app(AccountManager::class);
        $request->tempUser = $accountManager->findOrCreateIncompleteRegistrationUser($request->claims->getClaim('email_phone'), $request->claims->getClaim('input_type'));

        if ($request->tempUser->isCompleteRegistrationUser()) {
            if (is_null(config(Constants::$configLoginOptions)))
                return RS::back2Auth(__('novel-auth::messages.login.disabled'));
            if (!$request->tempUser->isActive())
                return RS::back2Auth($request->tempUser->inActiveMessage());
        } else {
            if (empty(config(Constants::$configRegisterMethods)))
                return RS::back2Auth(__('novel-auth::messages.register.disabled'));
            if (!in_array($request->claims->getClaim('input_type'), config(Constants::$configRegisterMethods)))
                return RS::back2Auth(__('novel-auth::messages.register.with_' . ($request->claims->getClaim('input_type') == Constants::$EMAIL_MODE ? 'phone' : 'email')));
        }
        return $next($request);
    }

}
