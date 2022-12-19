@extends('novel-auth::bootstrap.layout')
@section('content')

    <form method="post" action="{{route('auth.auth')}}">
        @csrf
        <input type="hidden" name="token_rc" value="{{$token_rc->toString()}}">

        <div class="mb-3">{{ $message ?? '' }}</div>

        <div dir="ltr" class="form-floating">
            <input type="password" name="pass" class="form-control" id="floatingInput" placeholder="{{ __('novel-auth::messages.password') }}" required>
            <label for="floatingInput">{{ __('novel-auth::messages.password') }}</label>
        </div>

        <button class="w-100 btn btn-lg btn-primary mt-3" type="submit">{{ __('novel-auth::messages.submit') }}</button>

        @if($canOtp)
            <button onclick="change2Otp()" type="button" class="btn btn-link">{{ __('novel-auth::messages.login_with_otp') }}</button>
            <script>
                function change2Otp() {
                    let form = document.getElementsByTagName('form')[0];
                    document.getElementsByName('pass')[0].value = '';

                    let input = document.createElement('input');
                    input.setAttribute('name', 'force_otp_type');
                    input.setAttribute('value', 'otp_options');
                    input.setAttribute('type', 'hidden');
                    form.appendChild(input);

                    form.submit();
                }
            </script>
        @endif
    </form>
@endsection
