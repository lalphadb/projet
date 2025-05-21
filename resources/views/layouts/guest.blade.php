<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Authentification - StudiosUnisDB')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- CSS Vendor -->
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">
    
    <!-- CSS Personnalisés -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">

    @stack('styles')
</head>
<body class="hold-transition login-page dark-mode">
    @yield('content')

    <!-- Scripts Vendor -->
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>
    
    <!-- Scripts Personnalisés -->
    <script src="{{ asset('js/auth.js') }}"></script>

    @stack('scripts')
</body>
</html>
