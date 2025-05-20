<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Mikuman - Mikrotik User Manager</title>
        <link rel="icon" type="image/x-icon" href="/favicon.svg">

        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @yield('js')
    </head>
    <body class="relative flex min-h-screen flex-col antialiased">
        <main class="flex-1 bg-base-100">
            @if (auth()->guest())
            <div class="container mx-auto max-w-screen-2xl">
                <div class="bg-base-200" id="content">
            @else
            @include('includes.header')
            <div class="container mx-auto max-w-screen-2xl grid grid-cols-1 md:grid-cols-[200px_minmax(0,1fr)] gap-4">
                @include('includes.sidebar')
                <div class="md:rounded-tl-2xl min-h-[calc(100vh-4rem)] bg-base-200 p-6" id="content">
            @endif
                    @yield('content')
                </div>
            </div>
        </main>
    </body>
</html>