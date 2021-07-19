@extends('novel-auth::auth.layout')
@section('h3') Password and Confirmation @endsection

@section('content')
    <form method="post" action="{{route('auth.attempt')}}">
        @csrf
        <input type="hidden" name="token_rc" value="{{$token_rc}}">
        <div>
            <label>Pass:
                <input name="pass" required>
            </label>
        </div>
        <div>
            <label>Pass conf:
                <input name="pass_conf" required>
            </label>
        </div>
        <input type="submit">
    </form>

@endsection
