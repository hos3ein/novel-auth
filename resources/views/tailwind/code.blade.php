@extends(\Hos3ein\NovelAuth\NovelAuth::tailwindLayout())
@section('content')
    <x-novel-auth::tailwind.errors :$errors/>

    <x-novel-auth::tailwind.message :$message/>

    <form class="flex flex-col" method="post" action="{{ route('auth.auth') }}">
        @csrf
        <input type="hidden" name="token_rc" value="{{ $token_rc->toString() }}">

        <label class="font-medium text-sm text-gray-700">{{ __('novel-auth::messages.code') }}
            <input name="code"
                   class="mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                   type="text"
                   dir="ltr"
                   placeholder="__   __   __   __"
                   required autofocus>
        </label>

        <x-novel-auth::tailwind.button-resend-otp :$otpType :$ttl/>

        <x-novel-auth::tailwind.button-submit/>

        <div class="flex justify-between">
            <x-novel-auth::tailwind.button-otp-options :$otpOptions/>
            <x-novel-auth::tailwind.button-with-password :$canPassword/>
        </div>
    </form>
@endsection
