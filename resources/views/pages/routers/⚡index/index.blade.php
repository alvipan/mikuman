<x-container>

    <x-slot:title>
        <flux:heading size="lg">
            Routers
        </flux:heading>
    </x-slot:title>

    <x-slot:filters>
        <flux:input icon="magnifying-glass" placeholder="Search router" wire:model.live.debounce.300ms="search" />
    </x-slot:filters>

    <x-slot:actions>
        <flux:button icon="plus" wire:click="create" wire:target='create'>
            Add
        </flux:button>
    </x-slot:actions>

    <flux:card size="sm">
        <flux:table>

            <flux:table.columns>
                <flux:table.column>Nama</flux:table.column>
                <flux:table.column>Host</flux:table.column>
                <flux:table.column>Port</flux:table.column>
                <flux:table.column>Status</flux:table.column>
                <flux:table.column></flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse ($routers as $router)
                    @php
                        $status = $this->routerStatus($router);
                    @endphp
                    <flux:table.row>
                        <flux:table.cell>{{ $router->name }}</flux:table.cell>
                        <flux:table.cell>{{ $router->host }}</flux:table.cell>
                        <flux:table.cell>{{ $router->port }}</flux:table.cell>
                        <flux:table.cell>

                            @if ($status === "ready")
                                <flux:badge size="sm" color="green">
                                    Ready
                                </flux:badge>
                            @elseif($status === "partial")
                                <flux:badge size="sm" color="yellow">
                                    Partial
                                </flux:badge>
                            @elseif($status === "not-setup")
                                <flux:badge size="sm" color="orange">
                                    Not Setup
                                </flux:badge>
                            @else
                                <flux:badge size="sm" color="red">
                                    Offline
                                </flux:badge>
                            @endif

                        </flux:table.cell>

                        <flux:table.cell class="flex items-center justify-end gap-2">
                            @if ($status != "ready")
                                <flux:button size="sm" variant="primary" color="blue"
                                    wire:click="setupRouter({{ $router->id }})">
                                    Setup
                                </flux:button>
                            @endif

                            <flux:button size="sm" wire:click="edit({{ $router->id }})"
                                wire:target="edit({{ $router->id }})">
                                Edit
                            </flux:button>

                            <flux:button size="sm" variant="danger" wire:click="delete({{ $router->id }})"
                                wire:target="delete({{ $router->id }})">
                                Delete
                            </flux:button>

                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="4" class="text-center">
                            <div class="space-y-4 p-4">
                                <p>No router data found.</p>
                                <flux:button icon="plus" wire:click="create" wire:target='create'>
                                    Add Router
                                </flux:button>
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>

        </flux:table>
    </flux:card>

    <flux:modal wire:model="showModal" class="w-sm">

        <flux:heading size="lg">
            {{ $routerId ? "Edit Router" : "Tambah Router" }}
        </flux:heading>

        <div class="mt-4 space-y-4">

            <flux:input label="Nama Router" wire:model="name" />

            <flux:input label="Host / IP" wire:model="host" />

            <flux:input label="Port" type="number" wire:model="port" />

            <flux:input label="Username" wire:model="username" autocomplete="username" />

            <flux:input label="Password" type="password" wire:model="password" autocomplete="new-password" viewable />

        </div>

        <div class="mt-6 flex justify-end gap-2">

            <flux:button variant="ghost" wire:click="$set('showModal', false)">
                Batal
            </flux:button>

            <flux:button variant="primary" wire:click="save">
                Simpan
            </flux:button>

        </div>

    </flux:modal>

</x-container>
