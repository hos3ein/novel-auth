@extends('novel-auth::auth.layout')
@section('h3') Code @endsection

@section('content')
    <form method="post" action="{{route('auth.attempt')}}">
        @csrf
        <input type="hidden" name="token_rc" value="{{$token_rc}}">
        <label>Code:
            <input name="code" required>
        </label>
        <input type="submit">
    </form>

    @if(!in_array($otpType, [\Hos3ein\NovelAuth\Features\Constants::$OTP_GENERATOR, \Hos3ein\NovelAuth\Features\Constants::$OTP_USSD]))
        <button id="#btn_resend" onclick="resendCode()" disabled>Resend otp ({{ $ttl }})</button>
        <script>
            window.onload = function () {
                let sec = parseInt('{{ $ttl }}');
                if (sec === 0) {
                    document.getElementById('#btn_resend').innerText = 'Resend otp';
                    document.getElementById('#btn_resend').removeAttribute('disabled');
                } else if (sec > 0) {
                    let countDown = setInterval(() => {
                        sec--;
                        document.getElementById('#btn_resend').innerText = 'Resend otp (' + sec + ')';
                        if (sec === 0) {
                            clearInterval(countDown);
                            document.getElementById('#btn_resend').innerText = 'Resend otp';
                            document.getElementById('#btn_resend').removeAttribute('disabled');
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
    <br>
    @if(count($otpOptions)>1)
        <button onclick="change2Otp()">Change otp</button>
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
    <br>
    @if($canPassword)
        <button onclick="change2pass()">change to password mode</button>
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
@endsection
