<?php

namespace Hos3ein\NovelAuth\Http\Controllers;

use Hos3ein\NovelAuth\Classes\CheckLoginRegister;
use Hos3ein\NovelAuth\Classes\InputValidation;
use Hos3ein\NovelAuth\Classes\LoginCodePassword;
use Hos3ein\NovelAuth\Classes\LoginOptionalCodePass;
use Hos3ein\NovelAuth\Classes\LoginPasswordCode;
use Hos3ein\NovelAuth\Classes\RegisterCodePassword;
use Hos3ein\NovelAuth\Classes\RegisterOnlyPassword;
use Hos3ein\NovelAuth\Contracts\LogoutResponse;
use Hos3ein\NovelAuth\Features\Constants;
use Hos3ein\NovelAuth\NovelAuth;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Routing\Controller;

class AuthController extends Controller
{
    public function create()
    {
        $message = __('novel-auth::messages.auth');
        return view(NovelAuth::authView(), compact('message'));
    }

    public function store(Request $request)
    {
        return (new Pipeline(app()))->send($request)->through(array_filter([
            InputValidation::class,
            CheckLoginRegister::class,
            RegisterOnlyPassword::class,
            RegisterCodePassword::class, // register only code and code_pass
            LoginPasswordCode::class, // login only pass and pass_code and user force both
            LoginCodePassword::class, // login only code and code_pass and user force both
            LoginOptionalCodePass::class // optional login code or pass
        ]))->then(function () {
            abort(403, 'You should not see this message, if you see it, it means you have developed a mistake');
        });
    }

    public function destroy(Request $request)
    {
        auth(config(Constants::$configGuard))->logout();

        // $request->session()->invalidate();

        // $request->session()->regenerateToken();

        return app(LogoutResponse::class);
    }
}
