@extends('novel-auth::auth.layout')
@section('h3') Pass @endsection

@section('content')
    <form method="post" action="{{route('auth.attempt')}}">
        @csrf
        <input type="hidden" name="token_rc" value="{{$token_rc}}">
        <label>Password:
            <input name="pass" required>
        </label>
        <input type="submit">
    </form>

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
@endsection
