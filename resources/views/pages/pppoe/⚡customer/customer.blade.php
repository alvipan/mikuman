<x-container>
    <div class="space-y-4">
        <flux:card>
            <div class="flex items-start justify-between">
                <div>
                    <div class="text-lg font-semibold">{{ $customer->name }}</div>
                    <div class="text-sm text-zinc-400">{{ $customer->phone }}</div>
                    <div class="text-sm text-zinc-400">{{ $customer->address }}</div>
                </div>

                <flux:badge size="sm" color="{{ $customer->status === 'active' ? 'green' : 'red' }}">
                    {{ $customer->status === 'active' ? 'Active' : 'Inactive' }}
                </flux:badge>
            </div>
        </flux:card>

        <flux:card>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="font-semibold">
                        PPPoE Secrets
                    </h2>
                </div>

                <flux:button wire:click="createSecret" size="sm">
                    Tambah Secret
                </flux:button>
            </div>

            <flux:table>
                <flux:table.columns>
                    <flux:table.column>Username</flux:table.column>
                    <flux:table.column>Profile</flux:table.column>
                    <flux:table.column>Status</flux:table.column>
                    <flux:table.column>Expired</flux:table.column>
                    <flux:table.column></flux:table.column>
                </flux:table.columns>

                <flux:table.rows>

                    @forelse ($customer->secrets as $secret)
                        @php
                            $isExpired = $secret->expired_at && $secret->expired_at->isPast();
                        @endphp

                        <flux:table.row>

                            <flux:table.cell>
                                {{ $secret->username }}
                            </flux:table.cell>

                            <flux:table.cell>
                                {{ $secret->profile?->name ?? '-' }}
                            </flux:table.cell>

                            <flux:table.cell>

                                @if ($secret->disabled)
                                    <flux:badge color="red">
                                        Suspended
                                    </flux:badge>
                                @elseif ($isExpired)
                                    <flux:badge color="yellow">
                                        Expired
                                    </flux:badge>
                                @else
                                    <flux:badge color="green">
                                        Active
                                    </flux:badge>
                                @endif

                            </flux:table.cell>

                            <flux:table.cell>
                                {{ $secret->expired_at?->format('d M Y H:i') ?? '-' }}
                            </flux:table.cell>

                            <flux:table.cell>

                                <div class="flex justify-end gap-2">

                                    <flux:button size="sm" wire:click="editSecret({{ $secret->id }})">
                                        Edit
                                    </flux:button>

                                    <flux:button size="sm" wire:click="pay({{ $secret->id }})"
                                        wire:confirm="Terima pembayaran?">
                                        Bayar
                                    </flux:button>

                                    @if ($secret->disabled)
                                        <flux:button size="sm" variant="primary"
                                            wire:click="activateSecret({{ $secret->id }})">
                                            Activate
                                        </flux:button>
                                    @else
                                        <flux:button size="sm" variant="danger"
                                            wire:click="suspendSecret({{ $secret->id }})"
                                            wire:confirm="Suspend secret ini?">
                                            Suspend
                                        </flux:button>
                                    @endif

                                    <flux:button size="sm" variant="danger"
                                        wire:click="deleteSecret({{ $secret->id }})"
                                        wire:confirm="Hapus secret ini?">
                                        Hapus
                                    </flux:button>

                                </div>

                            </flux:table.cell>

                        </flux:table.row>

                    @empty

                        <flux:table.row>

                            <flux:table.cell colspan="5">

                                <div class="py-6 text-center text-zinc-500">
                                    Belum ada secret.
                                </div>

                            </flux:table.cell>

                        </flux:table.row>
                    @endforelse

                </flux:table.rows>
            </flux:table>

        </flux:card>
    </div>

    <flux:modal wire:model="showSecretModal">

        <div class="space-y-4">

            <h2 class="text-lg font-semibold">
                {{ $editingSecretId ? 'Edit Secret' : 'Add Secret' }}
            </h2>

            <flux:select label="Router" wire:model="secretForm.router_id" wire:change="loadProfiles">
                <option value="">Select Router</option>

                @foreach ($routers as $router)
                    <option value="{{ $router->id }}">
                        {{ $router->name }}
                    </option>
                @endforeach
            </flux:select>

            <flux:select label="Package" wire:model="secretForm.profile_id">
                <option value="">
                    Select Package
                </option>

                @foreach ($profiles as $profile)
                    <option value="{{ $profile->id }}">
                        {{ $profile->name }}
                    </option>
                @endforeach
            </flux:select>

            <div class="flex gap-4">

                <flux:input label="Username" wire:model="secretForm.username" />

                <flux:input label="Password" wire:model="secretForm.password" />

            </div>

            <div class="flex gap-4">

                <flux:input label="Local Address" wire:model="secretForm.local_address" />

                <flux:input label="Remote Address" wire:model="secretForm.remote_address" />

            </div>

            <div class="flex justify-end gap-2">

                <flux:button wire:click="$set('showSecretModal', false)">
                    Batal
                </flux:button>

                <flux:button variant="primary" wire:click="saveSecret">
                    Simpan
                </flux:button>

            </div>

        </div>

    </flux:modal>
</x-container>
