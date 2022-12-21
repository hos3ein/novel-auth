@if($canOtp)
    <button type="button"
            onclick="change2Otp()"
            class="mt-4 self-start text-gray-600 hover:text-gray-900 underline">{{ __('novel-auth::messages.login_with_otp') }}</button>
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
