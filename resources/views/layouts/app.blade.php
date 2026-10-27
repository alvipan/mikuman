<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ \App\Models\Setting::get('app_name', 'Mikuman') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @fluxAppearance
    </head>

    <body class="min-h-screen bg-zinc-50 text-zinc-900 antialiased dark:bg-zinc-900 dark:text-zinc-100">

        <!-- Sidebar & Konten Utama -->
        <div class="flex h-screen overflow-hidden">

            <!-- Sidebar -->
            <aside class="flex w-64 flex-col border-r border-zinc-800 bg-zinc-900 text-zinc-300">
                <!-- Logo -->
                <div class="flex h-16 items-center border-b border-zinc-800 px-6 text-lg font-bold text-white">
                    <flux:icon name="wifi" class="mr-2 text-indigo-500" />
                    {{ \App\Models\Setting::get('app_name', 'Mikuman') }}
                </div>

                <!-- Menu Navigasi -->
                <nav class="flex-1 space-y-1 overflow-y-auto px-4 py-4">
                    <flux:navlist>
                        <flux:navlist.item icon="home" href="/dashboard" :current="request()->is('dashboard')"
                            wire:navigate>
                            Dashboard</flux:navlist.item>
                        <flux:navlist.item icon="server" href="/routers" :current="request()->is('routers*')"
                            wire:navigate>
                            Routers</flux:navlist.item>
                        <flux:navlist.item icon="users" href="/resellers" :current="request()->is('resellers*')"
                            wire:navigate>
                            Resellers</flux:navlist.item>
                        <flux:navlist.item icon="wifi" href="/hotspot/vouchers" :current="request()->is('hotspot*')"
                            wire:navigate>
                            Hotspot
                        </flux:navlist.item>
                        <flux:navlist.item icon="signal" href="/pppoe" :current="request()->is('pppoe*')"
                            wire:navigate>
                            PPPoE
                        </flux:navlist.item>
                        <flux:navlist.item icon="document-text" href="/reports" :current="request()->is('reports*')"
                            wire:navigate>
                            Reports</flux:navlist.item>
                    </flux:navlist>

                    <div class="mt-8">
                        <flux:navlist>
                            <flux:navlist.item icon="cog" href="/settings" :current="request()->is('settings*')"
                                wire:navigate>
                                Settings</flux:navlist.item>
                            <flux:navlist.item icon="question-mark-circle" href="/help"
                                :current="request()->is('help*')" wire:navigate>
                                Help</flux:navlist.item>
                        </flux:navlist>
                    </div>
                </nav>

                <!-- User Profile / Logout -->
                <div class="border-t border-zinc-800 p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div
                                class="flex h-8 w-8 items-center justify-center rounded-full bg-indigo-600 font-bold text-white">
                                {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
                            </div>
                            <div>
                                <div class="text-sm font-medium text-white">{{ auth()->user()->name ?? 'Admin' }}</div>
                                <div class="text-xs text-zinc-500">{{ auth()->user()->role ?? 'Superadmin' }}</div>
                            </div>
                        </div>
                        <form method="POST" action="/logout">
                            @csrf
                            <button type="submit" class="text-zinc-500 hover:text-red-500">
                                <flux:icon.arrow-right-start-on-rectangle />
                            </button>
                        </form>
                    </div>
                </div>
            </aside>

            <!-- Area Konten -->
            <main class="flex flex-1 flex-col overflow-hidden">
                <!-- Topbar -->
                <header
                    class="flex h-16 items-center justify-between border-b border-zinc-200 bg-white px-6 dark:border-zinc-800 dark:bg-zinc-900">
                    <h1 class="text-xl font-semibold">{{ $title ?? 'Dashboard' }}</h1>
                    <div class="flex items-center space-x-4">
                        <!-- Tombol Switch Theme -->
                        <flux:button variant="ghost" icon="moon"
                            x-on:click="$flux.appearance = ($flux.appearance === 'dark' ? 'light' : 'dark')" />
                    </div>
                </header>

                <!-- Konten Dinamis (Livewire) -->
                <div class="flex-1 overflow-y-auto p-6">
                    {{ $slot }}
                </div>
            </main>
        </div>

        @fluxScripts

        <!-- Toast Component -->
        <livewire:toast />
    </body>

</html>
