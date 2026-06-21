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
        <x-sidebar />

        <x-navbar />

        <flux:main>
            <x-container>
                {{ $slot }}
            </x-container>
        </flux:main>

        <div x-data="{ show: false, message: '', type: 'info' }"
            x-on:toast.window="
                message = $event.detail.message;
                type = $event.detail.type || 'info';
                show = true;
                setTimeout(() => show = false, 3000);
            "
            x-show="show" x-transition class="fixed bottom-5 right-5 z-50 rounded-lg px-4 py-2 text-white"
            :class="{
                'bg-green-600': type === 'success',
                'bg-red-600': type === 'error',
                'bg-yellow-500 text-black': type === 'warning',
                'bg-zinc-800': type === 'info',
            }">
            <span x-text="message"></span>
        </div>

        @livewireScripts
        @fluxScripts

        <script>
            document.addEventListener('livewire:init', () => {
                Livewire.on('notify', (data) => {
                    window.dispatchEvent(new CustomEvent('toast', {
                        detail: data
                    }))
                })
            })
        </script>
    </body>

</html>
