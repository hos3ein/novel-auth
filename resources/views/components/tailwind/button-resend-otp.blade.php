@if(!in_array($otpType, [\Hos3ein\NovelAuth\Features\Constants::$OTP_GENERATOR, \Hos3ein\NovelAuth\Features\Constants::$OTP_USSD]))
    <button type="button"
            id="btn_resend"
            onclick="resendCode()"
            class="mt-4 text-sm text-gray-600 hover:text-gray-900 underline disabled:no-underline disabled:cursor-not-allowed"
            disabled>
        {{ __('novel-auth::messages.resend_otp') }} ({{ $ttl }})
    </button>
    <script>
        window.onload = function () {
            let sec = parseInt('{{ $ttl }}');
            if (sec === 0) {
                document.getElementById('btn_resend').innerText = '{{ __('novel-auth::messages.resend_otp') }}';
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
