<!DOCTYPE html>
<html lang="{{ session('locale', 'en') }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Diavox') }}</title>

    <!-- Page Builder required assets -->
    <link rel="stylesheet" href="{{ asset('assets/css/page-builder.css') }}">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <script src="{{ asset('assets/js/page-builder.js') }}"></script>

    <!-- Dynamically load assets depending on the template selected... -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    @yield('content')
</body>

</html>
