<html>
<head>
    <style>
        td {
            border: 1px solid;
            padding: 8px;
        }
    </style>
</head>
<body>
@if (session('status'))
    <div class="mb-4 font-medium text-sm text-green-600">
        {{ session('status') }}
    </div>
@endif

<table style="margin: 0 auto; border-collapse: collapse; width: 400px">
    <tr>
        <td>
            <button onclick="window.location.href ='{{route('auth.auth')}}';">Back</button>
            <h4 style="display: inline">@yield('h3')</h4>
        </td>
    </tr>
    <tr>
        <td>
            @if(count($errors) > 0)
                @foreach($errors->getMessages() as $key => $error)
                    @foreach($error as $e)
                        <div style="color: palevioletred;">{{ $e }}</div>
                    @endforeach
                @endforeach
            @endif
        </td>
    </tr>
    <tr>
        <td>{{ $message ?? '' }}</td>
    </tr>
    <tr>
        <td>@yield('content')</td>
    </tr>
</table>


{{--<hr style="margin-top: 100px">--}}
{{--{{$token_rc ?? ''}}--}}
{{--@if($token_rc ?? '')--}}
{{--    <pre>{{ json_encode($token_rc->getClaims(), JSON_PRETTY_PRINT) }}</pre>--}}
{{--@endif--}}
</body>
</html>
