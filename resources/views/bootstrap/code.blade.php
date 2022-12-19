@extends('novel-auth::bootstrap.layout')
@section('content')

    <form method="post" action="{{route('auth.auth')}}">
        @csrf
        <input type="hidden" name="token_rc" value="{{$token_rc->toString()}}">

        <div class="mb-3">{{ $message ?? '' }}</div>

        <div dir="ltr" class="form-floating mb-3">
            <input name="code" class="form-control" id="floatingInput" placeholder="{{ __('novel-auth::messages.code') }}" required>
            <label for="floatingInput">{{ __('novel-auth::messages.code') }}</label>
        </div>

        @if(!in_array($otpType, [\Hos3ein\NovelAuth\Features\Constants::$OTP_GENERATOR, \Hos3ein\NovelAuth\Features\Constants::$OTP_USSD]))
            <button id="btn_resend" onclick="resendCode()" class="btn btn-link" disabled>{{ __('novel-auth::messages.resend_otp') }} ({{ $ttl }})</button>
            <script>
                window.onload = function () {
                    let sec = parseInt('{{ $ttl }}');
                    if (sec === 0) {
                        document.getElementById('btn_resend').innerText = 'Resend otp';
                        document.getElementById('btn_resend').removeAttribute('disabled');
                    } else if (sec > 0) {
                        let countDown = setInterval(() => {
                            sec--;
                            document.getElementById('btn_resend').innerText = '{{ __('novel-auth::messages.resend_otp') }} (' + sec + ')';
                            if (sec === 0) {
                                clearInterval(countDown);
                                document.getElementById('btn_resend').innerText = '{{ __('novel-auth::messages.resend_otp') }}';
                                document.getElementById('btn_resend').removeAttribute('disabled');
                            }
                        }, 1000);
                    }
                };

                function resendCode() {
                    let form = document.getElementsByTagName('form')[0];
                    document.getElementsByName('code')[0].value = '';

                    let input = document.createElement('input');
                    input.setAttribute('name', 'force_otp_type');
                    input.setAttribute('value', '{{ $otpType }}');
                    input.setAttribute('type', 'hidden');
                    form.appendChild(input);
                    form.submit();
                }
            </script>
        @endif
        <button class="w-100 btn btn-lg btn-primary" type="submit">{{ __('novel-auth::messages.submit') }}</button>

        <div class="d-flex justify-content-between">
            @if(count($otpOptions)>1)
                <button onclick="change2Otp()" class="btn btn-link">{{ __('novel-auth::messages.select_another_otp') }}</button>
                <script>
                    function change2Otp() {
                        let form = document.getElementsByTagName('form')[0];
                        document.getElementsByName('code')[0].value = '';

                        let input = document.createElement('input');
                        input.setAttribute('name', 'force_otp_type');
                        input.setAttribute('value', 'otp_options');
                        input.setAttribute('type', 'hidden');
                        form.appendChild(input);

                        form.submit();
                    }
                </script>
            @endif

            @if($canPassword)
                <button onclick="change2pass()" class="btn btn-link">{{ __('novel-auth::messages.login_with_password') }}</button>
                <script>
                    function change2pass() {
                        let form = document.getElementsByTagName('form')[0];
                        document.getElementsByName('code')[0].value = '';

                        let input = document.createElement('input');
                        input.setAttribute('name', 'force_otp_type');
                        input.setAttribute('value', 'password');
                        input.setAttribute('type', 'hidden');
                        form.appendChild(input);

                        form.submit();
                    }
                </script>
            @endif
        </div>
    </form>
@endsection
