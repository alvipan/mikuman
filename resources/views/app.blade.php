<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Mikuman - Mikrotik User Manager</title>
        <link rel="icon" type="image/x-icon" href="/favicon.svg">

        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="relative flex min-h-screen flex-col antialiased">
        <main class="flex-1 bg-base-content/10">
            @yield('content')
        </main>
    </body>
</html>