@php
$theme = 'dark';
if (session('router')) {
    $router = App\Models\Router::firstWhere('host', session('router'));
    $theme = $router->theme;
}
@endphp
<!DOCTYPE html>
<html data-theme="{{$theme}}">
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
    <body class="relative flex min-h-screen flex-col antialiased overflow-hidden">
        <main class="flex-1 bg-base-100">
            @if (!session('router'))
            <div class="container mx-auto max-w-screen-2xl">
                <div class="bg-base-200 min-h-screen" id="content">
            @else
            <x-part.header/>
            <x-drawer.setting />
            <div class="container mx-auto max-w-screen-2xl grid grid-cols-1 md:grid-cols-[200px_minmax(0,1fr)]">
                <x-drawer.sidebar menu="{{$menu}}" submenu="{{$submenu}}"/>
                <div class="flex flex-col h-[calc(100vh-4rem)] bg-base-200 md:rounded-tl-2xl 2xl:rounded-t-2xl" id="content">
            @endif
                    @yield('content')
                    <div id="alert" class="max-md:w-full fixed bottom-0 right-0 p-6 z-100">
                        <div class="alert alert-success py-2 flex items-start gap-2 hidden">
                            <span class="icon"></span>
                            <span class="message"></span>
                        </div>
                        <div class="alert alert-error py-2 flex items-start gap-2 hidden">
                            <span class="icon"></span>
                            <span class="message"></span>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        @if (session('router'))
        <x-modal.expire-monitor-form/>
        @endif
        @yield('modal')
    </body>
</html>