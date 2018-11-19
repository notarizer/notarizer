<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Notarizer -- Instantly timestamp yadayada</title>

    <link rel="stylesheet" href="{{ mix('/css/app.css') }}">

</head>
<body>
    <div id="app">
        <h1><a href="{{ route('home') }}">Notarizer</a></h1>
        
        @if(session()->has('payment_confirmation'))
            <div>
                <p>{{ session('payment_confirmation') }}</p>
            </div>
        @endif

        @yield('content')
    </div>

    <script src="{{ mix('/js/app.js') }}"></script>

    @stack('scripts')
</body>
</html>
