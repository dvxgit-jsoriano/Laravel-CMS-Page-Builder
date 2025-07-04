<!DOCTYPE html>
<html lang="{{ session('locale', 'en') }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Diavox') }}</title>

    <!-- Page Builder required assets -->
    <link rel="stylesheet" href="{{ asset('assets/css/page-builder.css') }}">

    @stack('styles')

    @stack('scripts')
</head>

<body>
    @yield('content')
</body>

</html>
