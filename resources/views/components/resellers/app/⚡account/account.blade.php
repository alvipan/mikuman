<div class="space-y-4">

    {{-- PROFILE --}}
    <flux:callout color="blue">
        <div class="flex items-center gap-4">
            <flux:avatar icon="user" size="lg" color="blue" />
            <div class="space-y-1">
                <flux:heading>{{ $name }}</flux:heading>
                <flux:badge rounded size="sm" color="blue">{{ ucfirst($role) }}</flux:badge>
            </div>
        </div>
    </flux:callout>

    <flux:callout>
        <div class="space-y-4">
            <flux:input label="Nama" type="text" wire:model="name" />
            <flux:input label="Username" type="text" wire:model="username" disabled />
            <flux:input label="Password" type="password" wire:model="password" viewable />
            <flux:button wire:click="save" wire:targer="save" class="w-full">
                Simpan
            </flux:button>
        </div>
    </flux:callout>

    {{-- LOGOUT --}}
    <flux:button wire:click="logout" class="w-full" variant="danger">
        Logout
    </flux:button>
</div>
