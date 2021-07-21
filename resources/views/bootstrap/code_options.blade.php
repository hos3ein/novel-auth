@extends('novel-auth::bootstrap.layout')
@section('content')
    <form method="post" action="{{route('auth.auth')}}">
        @csrf
        <input type="hidden" name="token_rc" value="{{$token_rc}}">

        <div class="mb-3">{{ $message ?? '' }}</div>

        <ul class="list-group px-0">
            @foreach($otpOptions as $k=>$option)
                <li class="list-group-item text-start p-0">
                    <label class="form-check-label d-block py-2 px-3" for="flexRadio{{$k}}">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="force_otp_type"
                                   value="{{ $option['type'] }}"
                                   id="flexRadio{{$k}}" required>
                            {{ $option['type'] }} ({{ $option['id'] }})
                        </div>
                    </label>
                </li>
            @endforeach
        </ul>

        <button class="w-100 btn btn-lg btn-primary mt-3" type="submit">{{ __('novel-auth::messages.submit') }}</button>

        @if($canPassword)
            <button onclick="change2pass()" type="button" class="btn btn-link">{{ __('novel-auth::messages.login_with_password') }}</button>
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
    </form>


@endsection
