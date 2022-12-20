@if($canPassword)
    <button onclick="change2pass()"
            class="btn btn-link">{{ __('novel-auth::messages.login_with_password') }}</button>
    <script>
        function change2pass() {
            let form = document.getElementsByTagName('form')[0];
            document.getElementsByName('code').forEach(e => e.value = '');

            let input = document.createElement('input');
            input.setAttribute('name', 'force_otp_type');
            input.setAttribute('value', 'password');
            input.setAttribute('type', 'hidden');
            form.appendChild(input);

            form.submit();
        }
    </script>
@endif
