@if(count($otpOptions) > 1)
    <button type="button"
            onclick="change2Otp()"
            class="mt-4 self-start text-gray-600 hover:text-gray-900 underline">{{ __('novel-auth::messages.select_another_otp') }}</button>
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
