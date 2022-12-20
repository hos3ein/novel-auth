@if($canOtp)
    <button onclick="change2Otp()">change to otp mode</button>
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
