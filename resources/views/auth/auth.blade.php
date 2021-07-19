@extends('novel-auth::auth.layout')
@section('h3') Login @endsection

@section('content')
    <form method="post" action="{{route('auth.auth')}}">
        @csrf
        <div>
            <label>Email/Phone:
                <input name="email_phone" required>
            </label>
        </div>
        <input type="submit">
    </form>

@endsection
