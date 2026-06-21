@placeholder
    <x-ui.skeleton.table rows="3" :cols="['Username', 'Customer', 'Profile', 'Status', '']" />
@endplaceholder

<div class="space-y-4">

    <flux:card class="space-y-4">

        {{-- HEADER --}}
        <div class="flex items-center justify-between">

            <flux:heading size="md">
                PPPoE Secrets
            </flux:heading>

            <flux:select size="sm" wire:model.live="router_id" class="w-48">
                @foreach ($this->routers as $r)
                    <option value="{{ $r->id }}">{{ $r->name }}</option>
                @endforeach
            </flux:select>

        </div>

        {{-- TABLE --}}
        <flux:table>

            <flux:table.columns>
                <flux:table.column>Username</flux:table.column>
                <flux:table.column>Customer</flux:table.column>
                <flux:table.column>Profile</flux:table.column>
                <flux:table.column>Status</flux:table.column>
                <flux:table.column></flux:table.column>
            </flux:table.columns>

            <flux:table.rows>

                @forelse ($this->secrets as $s)
                    <flux:table.row>

                        <flux:table.cell>
                            {{ $s->username }}
                        </flux:table.cell>

                        <flux:table.cell>
                            {{ $s->customer?->name }}
                        </flux:table.cell>

                        <flux:table.cell>
                            {{ $s->profile?->name }}
                        </flux:table.cell>

                        <flux:table.cell>
                            <flux:badge size="sm" rounded color="{{ $s->disabled ? 'red' : 'green' }}">
                                {{ $s->disabled ? 'Disabled' : 'Active' }}
                            </flux:badge>
                        </flux:table.cell>

                        <flux:table.cell class="text-right">
                            <div class="flex justify-end gap-2">

                                {{-- toggle --}}
                                <flux:button size="sm" wire:click="toggle({{ $s->id }})">
                                    {{ $s->disabled ? 'Enable' : 'Disable' }}
                                </flux:button>

                                {{-- delete --}}
                                <flux:button size="sm" variant="danger" wire:click="delete({{ $s->id }})"
                                    wire:confirm="Delete this secret?">
                                    Delete
                                </flux:button>

                            </div>
                        </flux:table.cell>

                    </flux:table.row>

                @empty

                    <flux:table.row>
                        <flux:table.cell colspan="5">

                            <div class="space-y-2 py-10 text-center">

                                <div class="text-sm text-gray-500">
                                    No PPPoE secrets found
                                </div>

                            </div>

                        </flux:table.cell>
                    </flux:table.row>
                @endforelse

            </flux:table.rows>

        </flux:table>

    </flux:card>

</div>
