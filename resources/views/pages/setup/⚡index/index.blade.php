<div class="flex min-h-screen items-center justify-center bg-zinc-50 dark:bg-zinc-900">
    <flux:card class="w-full max-w-sm">
        <div class="space-y-6">
            <header>
                <flux:heading size="xl">Mikuman Setup</flux:heading>
                <flux:subheading>Create an administrator account.</flux:subheading>
            </header>

            <form wire:submit="createAdmin" class="space-y-4">
                <flux:input wire:model="name" label="Full Nmae" />
                <flux:input wire:model="username" label="Username" />
                <flux:input wire:model="password" label="Password" type="password" autocomplete="new-password"
                    viewable />

                <flux:button type="submit" variant="primary" class="w-full">
                    Complete the Installation
                </flux:button>
            </form>
        </div>
    </flux:card>
</div>
