@extends('novel-auth::bootstrap.layout')
@section('content')
    <form method="post" action="{{route('auth.auth')}}">
        @csrf

        <div class="mb-3">{{ $message ?? '' }}</div>

        <div class="form-floating">
            <input name="email_phone" dir="ltr" pattern="^((\+\d{1,3})+\d{10})|([a-z.]+@\w+\.\w{3,})$" class="form-control" id="floatingInput" placeholder="{{ __('novel-auth::messages.email/phone') }}" required>
            <label for="floatingInput">{{ __('novel-auth::messages.email/phone') }}</label>
            <div class="form-text">e.g. +989123456789 | sample@gmail.com</div>
        </div>

        <button class="w-100 btn btn-lg btn-primary mt-3" type="submit">{{ __('novel-auth::messages.submit') }}</button>
    </form>
@endsection
