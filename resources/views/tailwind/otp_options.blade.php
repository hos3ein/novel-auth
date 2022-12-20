@extends(\Hos3ein\NovelAuth\NovelAuth::tailwindLayout())
@section('content')
    <x-novel-auth::tailwind.errors :$errors/>

    <x-novel-auth::tailwind.message :$message/>

    <form class="flex flex-col" method="post" action="{{ route('auth.auth') }}">
        @csrf
        <input type="hidden" name="token_rc" value="{{ $token_rc->toString() }}">

        <div>
            <div>Choose one:</div>
            @foreach($otpOptions as $option)
                <label>
                    <input type="radio" name="force_otp_type" value="{{ $option['type'] }}"> {{ $option['type'] }} ({{ $option['id'] }})
                </label><br>
            @endforeach
        </div>

        <x-novel-auth::tailwind.button-submit/>

        <x-novel-auth::tailwind.button-with-password :$canPassword/>
    </form>
@endsection
