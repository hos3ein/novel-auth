<?php

namespace Hos3ein\NovelAuth\Responses;

use App\Providers\RouteServiceProvider;
use Hos3ein\NovelAuth\Contracts\LogoutResponse as LogoutResponseContract;
use Illuminate\Http\JsonResponse;

class LogoutResponse implements LogoutResponseContract
{
    public function toResponse($request)
    {
        return $request->wantsJson()
            ? new JsonResponse(['status' => true], 204)
            : redirect('/');
    }
}
