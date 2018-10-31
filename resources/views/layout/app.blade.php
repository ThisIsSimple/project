<!doctype html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>하루 한 문제</title>

    <link rel="stylesheet" href="/css/app.css">
</head>
<body>
    @include('layout.header')

    @yield('content')

    <script src="/js/app.js"></script>
    @yield('script')
</body>
</html>