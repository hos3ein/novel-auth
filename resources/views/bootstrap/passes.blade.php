@extends('novel-auth::bootstrap.layout')
@section('content')
    <form method="post" action="{{route('auth.auth')}}">
        @csrf
        <input type="hidden" name="token_rc" value="{{$token_rc->toString()}}">

        <div class="mb-3">{{ $message ?? '' }}</div>

        <div dir="ltr" class="form-floating mb-3">
            <input type="password" name="pass" class="form-control" id="floatingInput" placeholder="{{ __('novel-auth::messages.password') }}" required>
            <label for="floatingInput">{{ __('novel-auth::messages.password') }}</label>
        </div>
        <div dir="ltr" class="form-floating">
            <input type="password" name="pass_conf" class="form-control" id="floatingInput" placeholder="{{ __('novel-auth::messages.password_confirmation') }}" required>
            <label for="floatingInput">{{ __('novel-auth::messages.password_confirmation') }}</label>
        </div>

        <button class="w-100 btn btn-lg btn-primary mt-3" type="submit">{{ __('novel-auth::messages.submit') }}</button>
    </form>
@endsection
