<x-container>

    <div class="space-y-4">

        <x-slot:title>
            <flux:heading size="lg">
                Resellers
            </flux:heading>
        </x-slot:title>

        <x-slot:filters>
            <flux:input icon="magnifying-glass" placeholder="Search reseller" wire:model.live.debounce.300ms="search" />
        </x-slot:filters>

        <x-slot:actions>
            <flux:button icon="plus" wire:click="create" wire:target='create'>
                Add
            </flux:button>
        </x-slot:actions>

        <div class="grid gap-4 md:grid-cols-4">

            <flux:card>
                <flux:subheading>Total Resellers</flux:subheading>

                <div class="text-2xl font-bold">
                    {{ $this->resellers->count() }}
                </div>
            </flux:card>

            <flux:card>
                <flux:subheading>Unused Vouchers</flux:subheading>

                <div class="text-2xl font-bold">
                    {{ number_format($this->resellers->sum('unused_vouchers_count')) }}
                </div>
            </flux:card>

            <flux:card>
                <flux:subheading>Expired Vouchers</flux:subheading>

                <div class="text-2xl font-bold">
                    {{ number_format($this->resellers->sum('expired_vouchers_count')) }}
                </div>
            </flux:card>

            <flux:card>
                <flux:subheading>Outstanding Commission</flux:subheading>

                <div class="text-2xl font-bold">
                    {{ money($this->resellers->sum('commission_total')) }}
                </div>
            </flux:card>

        </div>

        <flux:card>
            <flux:table>
                <flux:table.columns>
                    <flux:table.column>Name</flux:table.column>
                    <flux:table.column>Unused</flux:table.column>
                    <flux:table.column>Used</flux:table.column>
                    <flux:table.column>Expired</flux:table.column>
                    <flux:table.column>Commission</flux:table.column>
                    <flux:table.column></flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @forelse ($this->resellers as $reseller)
                        <flux:table.row :key="$reseller->id">

                            <flux:table.cell variant="strong">
                                {{ $reseller->name }}
                            </flux:table.cell>

                            <flux:table.cell>
                                {{ number_format($reseller->unused_vouchers_count) }}
                            </flux:table.cell>

                            <flux:table.cell>
                                {{ number_format($reseller->used_vouchers_count) }}
                            </flux:table.cell>

                            <flux:table.cell>
                                {{ number_format($reseller->expired_vouchers_count) }}
                            </flux:table.cell>

                            <flux:table.cell>
                                {{ money($reseller->commission_total ?? 0) }}
                            </flux:table.cell>

                            <flux:table.cell class="flex justify-end gap-2">

                                <flux:button size="sm" :href="route('resellers.detail', $reseller)" wire:navigate>
                                    Detail
                                </flux:button>

                                <flux:button size="sm" wire:click="edit({{ $reseller->id }})">
                                    Edit
                                </flux:button>

                                <flux:button size="sm" variant="danger" wire:click="delete({{ $reseller->id }})"
                                    wire:confirm="Delete this reseller?">
                                    Delete
                                </flux:button>

                            </flux:table.cell>

                        </flux:table.row>
                    @empty
                        <flux:table.row>
                            <flux:table.cell colspan="5" class="text-center">

                                <div class="space-y-4 p-4">

                                    <p>No reseller found.</p>

                                    <flux:button icon="plus" wire:click="create">
                                        Add Reseller
                                    </flux:button>

                                </div>

                            </flux:table.cell>
                        </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>
        </flux:card>

        <flux:modal wire:model="showModal" class="">

            <div class="space-y-4">

                <h3 class="text-lg font-semibold">
                    {{ $editId ? 'Edit Reseller' : 'Create Reseller' }}
                </h3>

                <flux:input label="Name" wire:model="name" autocomplete="name" />

                <flux:input label="Username" wire:model="username" autocomplete="username" />

                <flux:input label="Password" type="password" wire:model="password" viewable />

                <div class="flex justify-end gap-2 pt-2">

                    <flux:button variant="ghost" wire:click="$set('showModal', false)">
                        Cancel
                    </flux:button>

                    <flux:button wire:click="save">
                        Save
                    </flux:button>

                </div>

            </div>

        </flux:modal>
    </div>

</x-container>
