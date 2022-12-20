@extends(\Hos3ein\NovelAuth\NovelAuth::tailwindLayout())
@section('content')
    <x-novel-auth::tailwind.errors :$errors/>

    <x-novel-auth::tailwind.message :$message/>

    <form class="flex flex-col" method="post" action="{{ route('auth.auth') }}">
        @csrf

        <label class="font-medium text-sm text-gray-700">{{ __('novel-auth::messages.email/phone') }}
            <input name="email_phone"
                   class="mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                   type="text"
                   dir="ltr"
                   pattern="^((\+\d{1,3})+\d{10})|([1-9a-z.]+@\w+\.\w{3,})$"
                   placeholder="email / phone"
                   required autofocus>
        </label>

        <div class="mt-4">
            <label class="inline-flex items-center">
                <input name="remember"
                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                       type="checkbox">
                <span class="mr-2 text-sm text-gray-600">{{ __('novel-auth::messages.remember_me') }}</span>
            </label>
        </div>

        <x-novel-auth::tailwind.button-submit :value="__('novel-auth::messages.remember_me')"/>
    </form>
@endsection
