<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ \App\Models\Setting::get('app_name', 'Mikuman') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @fluxAppearance
    </head>

    <body
        class="flex min-h-screen items-center justify-center bg-zinc-100 text-zinc-900 antialiased dark:bg-zinc-950 dark:text-zinc-100">

        <!-- Konten Login -->
        {{ $slot }}

        @fluxScripts
    </body>

</html>
