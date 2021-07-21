@extends('novel-auth::bootstrap.layout')
@section('content')
    <form method="post" action="{{route('auth.auth')}}">
        @csrf

        <div class="mb-3">{{ $message ?? '' }}</div>

        <div class="form-floating">
            <input name="email_phone" class="form-control" id="floatingInput" placeholder="{{ __('novel-auth::messages.email/phone') }}" required>
            <label for="floatingInput">{{ __('novel-auth::messages.email/phone') }}</label>
        </div>

        <button class="w-100 btn btn-lg btn-primary mt-3" type="submit">{{ __('novel-auth::messages.submit') }}</button>
    </form>
@endsection
