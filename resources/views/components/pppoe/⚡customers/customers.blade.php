@placeholder
    <x-ui.skeleton.table rows="3" :cols="['Name', 'Phone', 'Address', 'Status', '']" />
@endplaceholder

<div class="space-y-4">

    <flux:card class="space-y-4">

        {{-- HEADER --}}
        <div class="flex items-center justify-between">
            <flux:heading size="md">
                Customers
            </flux:heading>

            <flux:button size="sm" icon="plus" wire:click="create">
                Add
            </flux:button>
        </div>

        {{-- TABLE --}}
        <flux:table>

            <flux:table.columns>
                <flux:table.column>Name</flux:table.column>
                <flux:table.column>Phone</flux:table.column>
                <flux:table.column>Address</flux:table.column>
                <flux:table.column>Status</flux:table.column>
                <flux:table.column></flux:table.column>
            </flux:table.columns>

            <flux:table.rows>

                @forelse ($this->customers as $c)
                    <flux:table.row>
                        <flux:table.cell>{{ $c->name }}</flux:table.cell>
                        <flux:table.cell>{{ $c->phone ?? '-' }}</flux:table.cell>
                        <flux:table.cell>{{ $c->address ?? '-' }}</flux:table.cell>
                        <flux:table.cell>
                            <flux:badge size="sm" rounded color="{{ $c->status === 'active' ? 'green' : 'red' }}">
                                {{ $c->status === 'active' ? 'Active' : 'Inactive' }}
                            </flux:badge>
                        </flux:table.cell>
                        <flux:table.cell class="text-right">
                            <div class="flex justify-end gap-2">

                                <flux:button size="sm" :href="route('pppoe.customer', $c->id)" wire:navigate>
                                    Detail
                                </flux:button>

                                <flux:button size="sm" variant="danger" wire:click="delete({{ $c->id }})"
                                    wire:confirm="Delete this customer?">
                                    Delete
                                </flux:button>

                            </div>
                        </flux:table.cell>
                    </flux:table.row>

                @empty

                    {{-- EMPTY STATE --}}
                    <flux:table.row>
                        <flux:table.cell colspan="4" class="text-center">

                            <div class="space-y-4 p-4">
                                <p>No customers yet.</p>

                                <flux:button icon="plus" wire:click="create" wire:target="create">
                                    Add Customer
                                </flux:button>
                            </div>

                        </flux:table.cell>
                    </flux:table.row>
                @endforelse

            </flux:table.rows>

        </flux:table>

    </flux:card>

    {{-- MODAL --}}

    <flux:modal wire:model="showModal" class="w-sm space-y-4">

        <flux:heading size="md">
            {{ $isEdit ? 'Edit Customer' : 'Add Customer' }}
        </flux:heading>

        {{-- CUSTOMER --}}
        <div class="grid grid-cols-2 gap-4">
            <flux:input label="Name" wire:model="name" />
            <flux:input label="Phone" wire:model="phone" />
        </div>

        <flux:textarea label="Address" wire:model="address" />

        {{-- ACTION --}}
        <div class="flex justify-end gap-2">
            <flux:button wire:click="$set('showModal', false)" variant="ghost">
                Cancel
            </flux:button>

            <flux:button wire:click="save">
                Save
            </flux:button>
        </div>

    </flux:modal>

</div>
