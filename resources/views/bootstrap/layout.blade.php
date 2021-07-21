<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#7952b3">

    <title>{{ config('app.name') }}</title>

    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <style>
        html,
        body {
            height: 100%;
        }

        body {
            display: flex;
            align-items: center;
            padding-top: 40px;
            padding-bottom: 40px;
            background-color: #f5f5f5;
        }

        .form-signin {
            width: 100%;
            max-width: 500px;
            padding: 15px;
            margin: auto;
        }

        .form-signin .checkbox {
            font-weight: 400;
        }

        .form-signin .form-floating:focus-within {
            z-index: 2;
        }

        .form-signin input[type="ss"] {
            margin-bottom: -1px;
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 0;
        }

        .form-signin input[type="dd"] {
            margin-bottom: 10px;
            border-top-left-radius: 0;
            border-top-right-radius: 0;
        }
    </style>
</head>
<body class="text-center" dir="{!! in_array(app()->getLocale(), ['fa']) ? 'rtl' : 'ltr' !!}">
<main class="form-signin">
    @if(count($errors) > 0)
        <div class="alert alert-danger" role="alert">
            <ul class="list-unstyled">
                @foreach($errors->getMessages() as $key => $error)
                    @foreach($error as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                @endforeach
            </ul>
        </div>
    @endif
    <div class="card">
        <div class="card-header">
            <ul class="nav nav-pills card-header-pills px-0">
                <li class="nav-item">
                    <a class="btn btn-primary" href="{{ url('/') }}">{{ __('novel-auth::messages.home') }}</a>
                </li>
                <li class="nav-item">
                    <a class="mx-2 btn btn-secondary" href="{{ route('auth.auth') }}">{{ __('novel-auth::messages.restart') }}</a>
                </li>
            </ul>
        </div>
        <div class="card-body">@yield('content')</div>
    </div>
</main>
</body>
</html>
