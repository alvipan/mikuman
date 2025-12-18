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

        <title>{{ $title ?? 'Mikuman' }}</title>
        <link rel="icon" type="image/x-icon" href="/favicon.svg" />
        
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="relative flex sm:min-h-screen flex-col antialiased sm:overflow-hidden">
        <main class="flex-1 bg-base-100">
            @if (!session('router'))
            <div class="container mx-auto max-w-screen-2xl">
                <div class="bg-base-200 min-h-screen" id="content">
            @else
            <x-part.header/>
            <x-drawer.setting />
            <div class="container mx-auto max-w-screen-2xl grid grid-cols-1 sm:grid-cols-[220px_minmax(0,1fr)]">
                <x-drawer.sidebar />
                <div id="content">
            @endif
                    {{ $slot }}
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

        @assets
        <script src="/assets/vendors/jquery/jquery.min.js"></script>
        <script src="/assets/vendors/datatables/datatables.min.js"></script>
        <script src="/assets/vendors/apexcharts/apexcharts.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        @endassets
    </body>
</html>
