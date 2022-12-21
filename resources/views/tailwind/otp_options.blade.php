@extends(\Hos3ein\NovelAuth\NovelAuth::tailwindLayout())
@section('content')
    <x-novel-auth::tailwind.errors :$errors/>

    <x-novel-auth::tailwind.message :$message/>

    <form class="flex flex-col" method="post" action="{{ route('auth.auth') }}">
        @csrf
        <input type="hidden" name="token_rc" value="{{ $token_rc->toString() }}">

        <ul>
            @foreach($otpOptions as $option)
                <li class="py-2">
                    <label class="py-2 cursor-pointer select-none">
                        <input type="radio" name="force_otp_type" value="{{ $option['type'] }}">
                        {{ \Hos3ein\NovelAuth\Responses\RS::otpLabel($option['type'], $option['id']) }}
                    </label>
                </li>
            @endforeach
        </ul>

        <x-novel-auth::tailwind.button-submit/>

        <x-novel-auth::tailwind.button-with-password :$canPassword/>
    </form>
@endsection
