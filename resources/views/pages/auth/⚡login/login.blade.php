<div class="flex min-h-screen items-center justify-center p-4">

    <flux:card class="w-full max-w-sm space-y-6">

        <flux:heading size="lg" class="text-center">Mikuman</flux:heading>

        <flux:separator text="Login to Mikuman" />

        <form wire:submit="login" class="space-y-4">

            <flux:input label="Username" wire:model="username" />

            <flux:input label="Password" type="password" wire:model="password" viewable />

            <flux:checkbox wire:model="remember" label="Remember me" />

            <flux:button type="submit" class="w-full">
                Login
            </flux:button>

        </form>

    </flux:card>

</div>
