<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>
            {{ $title ? $title . ' | ' : '' }} {{ config('app.name', 'MikuMan') }}
        </title>

        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net" rel="stylesheet" />

        <!-- Scripts & Styles (Vite) -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        {{-- Flux UI Styles --}}
        @livewireStyles
        @fluxAppearance
    </head>

    <body class="h-full bg-white font-sans text-zinc-900 antialiased dark:bg-zinc-900 dark:text-zinc-100">
        <div class="flex min-h-screen flex-col items-center sm:justify-center sm:pt-0">

            {{-- Isi Konten Setup --}}
            <main class="w-full">
                {{ $slot }}
            </main>
        </div>

        {{-- Flux UI Scripts --}}
        @livewireScripts
        @fluxScripts
    </body>

</html>
