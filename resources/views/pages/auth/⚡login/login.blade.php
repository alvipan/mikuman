<div class="mx-auto mt-20 w-full max-w-md">
    <div class="rounded-xl border border-zinc-200 bg-white p-8 shadow-lg dark:border-zinc-700 dark:bg-zinc-800">
        <div class="mb-8 text-center">
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">
                {{ \App\Models\Setting::get('app_name', 'Mikuman') }}</h1>
            <p class="mt-2 text-sm text-zinc-500">Masuk ke panel manajemen</p>
        </div>

        <form wire:submit="login" class="space-y-6">
            <flux:input wire:model="username" label="Username" type="text" placeholder="Masukkan username Anda"
                required />

            <flux:input wire:model="password" label="Password" type="password" placeholder="Masukkan password Anda"
                required />

            <div class="flex items-center justify-between">
                <flux:checkbox wire:model="remember" label="Ingat saya" />
            </div>

            <div class="flex items-center justify-end">
                <flux:button type="submit" variant="primary" class="w-full">
                    Masuk
                </flux:button>
            </div>
        </form>
    </div>
</div>
