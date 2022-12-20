@if(count($otpOptions) > 1)
    <button onclick="change2Otp()"
            class="btn btn-link">{{ __('novel-auth::messages.select_another_otp') }}</button>
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
