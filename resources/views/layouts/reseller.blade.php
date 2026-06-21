<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>
            {{ $title ? $title . ' | ' : '' }} {{ config('app.name', 'MikuMan') }}
        </title>

        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @livewireStyles
        @fluxAppearance

    </head>

    <body class="min-h-screen bg-white antialiased dark:bg-zinc-800">

        {{-- HEADER --}}
        <header class="sticky top-0 z-40 backdrop-blur">
            <div class="flex h-14 items-center justify-between px-4">
                <h1 class="text-lg font-semibold"> {{ auth()->user()->router->name ?? 'Mikuman' }} </h1>
            </div>
        </header>

        {{-- MAIN CONTENT --}}
        <main class="pb-18 overflow-y-auto">
            <div class="p-4"> {{ $slot }} </div>
        </main>
    </body>

    @livewireScripts
    @fluxScripts

</html>
