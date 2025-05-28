<!DOCTYPE html>
<html data-theme="dark">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />

        <title>Mikuman - Mikrotik User Manager</title>
        <link rel="icon" type="image/x-icon" href="/favicon.svg" />

        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @yield('js')
    </head>
    <body class="relative flex min-h-screen flex-col antialiased">
        <main class="flex-1 bg-base-100">
            @if (!session('router'))
            <div class="container mx-auto max-w-screen-2xl">
                <div class="bg-base-200 min-h-screen" id="content">
            @else
            @include('includes.header')
            <div class="container mx-auto max-w-screen-2xl grid grid-cols-1 md:grid-cols-[200px_minmax(0,1fr)] gap-4">
                @include('includes.sidebar')
                <div class="min-h-[calc(100vh-4rem)] bg-base-200 p-6" id="content">
                    <div class="bg-base-100 -mx-6 -mt-6 sticky top-16 z-50">
                        <div class="md:rounded-tl-2xl 2xl:rounded-t-2xl bg-base-200 h-4"></div>
                    </div>
            @endif
                    @yield('content')
                    <div id="alert" class="max-md:w-full fixed bottom-0 right-0 p-6"></div>
                </div>
            </div>
        </main>
        @yield('modal')
    </body>
</html>