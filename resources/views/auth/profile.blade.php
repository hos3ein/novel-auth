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
<table style="margin: 0 auto; border-collapse: collapse; width: 400px">
    <tr>
        <td>
            <h3>Profile</h3>
        </td>
        <td>
            <h5><a href="{{route('auth.logout')}}">Logout</a></h5>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <h2>{{ session('message', '') }}</h2>
        </td>
    </tr>
    <tr>
        <td>status</td>
        <td>{{ Auth()->user()->status == \Hos3ein\NovelAuth\Features\Constants::$STATUS_ACTIVE ? 'Active' : Auth()->user()->status}}</td>
    </tr>
    <tr>
        <td>name</td>
        <td>{{ Auth()->user()->name }}</td>
    </tr>
    <tr>
        <td>email</td>
        <td>{{ Auth()->user()->email }}</td>
    </tr>
    <tr>
        <td>phone</td>
        <td>{{ Auth()->user()->phone }}</td>
    </tr>
</table>

{{--<pre>{{ json_encode(Auth()->user(), JSON_PRETTY_PRINT) }}</pre>--}}

</body>
</html>
