<!DOCTYPE html>
<html>

<head>
    <title>Auth</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body style="background:#f5f5f5;">

    @yield('content')

    <script src="{{ asset('js/app.js') }}"></script>
</body>

</html>
