@extends('novel-auth::auth.layout')
@section('h3') Code Options @endsection

@section('content')
    <form method="post" action="{{route('auth.attempt')}}">
        @csrf
        <input type="hidden" name="token_rc" value="{{$token_rc}}">
        <div>Choose one:</div>
        @foreach($otpOptions as $option)
            <label>
            <input type="radio" name="force_otp_type" value="{{ $option['type'] }}"> {{ $option['type'] }} ({{ $option['id'] }})
            </label><br>
        @endforeach
        <input type="submit">
    </form>

    @if($canPassword)
        <button onclick="change2pass()">change to password mode</button>
        <script>
            function change2pass() {
                let form = document.getElementsByTagName('form')[0];

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
