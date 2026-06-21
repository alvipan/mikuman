@placeholder
    <x-ui.skeleton.table rows="3" :cols="['Router', 'Name', 'Rate Limit', 'Price', '']" />
@endplaceholder

<div class="space-y-4">

    <flux:card class="space-y-4">

        {{-- HEADER --}}
        <div class="flex items-center justify-between">

            <flux:heading size="md">
                PPPoE Packages
            </flux:heading>

            <flux:button icon="plus" size="sm" wire:click="create" wire:target="create">
                Add
            </flux:button>

        </div>

        {{-- TABLE --}}
        <flux:table>
            <flux:table.columns>
                <flux:table.column>Router</flux:table.column>
                <flux:table.column>Name</flux:table.column>
                <flux:table.column>Rate Limit</flux:table.column>
                <flux:table.column>Price</flux:table.column>
                <flux:table.column></flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse ($this->profiles as $p)
                    <flux:table.row :key="$p->id">

                        {{-- ROUTER --}}
                        <flux:table.cell>
                            {{ $p->router->name ?? '-' }}
                        </flux:table.cell>

                        {{-- NAME --}}
                        <flux:table.cell variant="strong">
                            {{ $p->name }}
                        </flux:table.cell>

                        {{-- RATE --}}
                        <flux:table.cell>
                            {{ $p->rate_limit ?? '-' }}
                        </flux:table.cell>

                        {{-- PRICE --}}
                        <flux:table.cell>
                            <span class="font-medium text-zinc-800 dark:text-zinc-200">
                                {{ money($p->price) }}
                            </span>
                        </flux:table.cell>

                        {{-- ACTION --}}
                        <flux:table.cell class="flex justify-end gap-2">
                            <flux:button size="sm" wire:click="edit({{ $p->id }})">
                                Edit
                            </flux:button>

                            <flux:button size="sm" variant="danger" wire:click="delete({{ $p->id }})">
                                Delete
                            </flux:button>
                        </flux:table.cell>

                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="6" class="text-center">
                            <div class="space-y-4 p-4">
                                <p>No packages yet.</p>

                                <flux:button icon="plus" wire:click="create" wire:target="create">
                                    Add Package
                                </flux:button>
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>

    </flux:card>

    {{-- MODAL --}}
    <flux:modal wire:model="showModal" class="w-sm">

        <div class="space-y-4">

            <flux:heading size="md">
                {{ $isEdit ? 'Edit Package' : 'Create Package' }}
            </flux:heading>

            {{-- ROUTER SELECT --}}
            <flux:select wire:model="form.router_id" label="Router" :disabled="$isEdit">
                <option value="">Select Router</option>

                @foreach ($this->routers as $r)
                    <option value="{{ $r->id }}">
                        {{ $r->name }}
                    </option>
                @endforeach
            </flux:select>

            <flux:input label="Profile Name" wire:model="form.name" placeholder="Basic-10M" />

            <flux:input label="Rate Limit" wire:model="form.rate_limit" placeholder="10M/10M" />

            <flux:field>
                <flux:label>Price</flux:label>
                <flux:input.group>
                    <flux:input.group.prefix>Rp</flux:input.group.prefix>
                    <flux:input type="number" wire:model="form.price" />
                </flux:input.group>
            </flux:field>

            <div class="flex justify-end">
                <flux:button wire:click="save">
                    Save
                </flux:button>
            </div>

        </div>

    </flux:modal>

</div>
