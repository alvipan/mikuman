<x-container>

    <div class="mb-4 grid gap-4 md:grid-cols-4">

        <flux:callout>
            <div class="text-sm text-zinc-500">
                Unused Vouchers
            </div>

            <div class="text-2xl font-bold">
                {{ number_format($this->unusedVouchers) }}
            </div>
        </flux:callout>

        <flux:callout>
            <div class="text-sm text-zinc-500">
                Used Vouchers
            </div>

            <div class="text-2xl font-bold">
                {{ number_format($this->usedVouchers) }}
            </div>
        </flux:callout>

        <flux:callout>
            <div class="text-sm text-zinc-500">
                Total Earned
            </div>

            <div class="text-2xl font-bold">
                {{ money($this->totalEarned) }}
            </div>
        </flux:callout>

        <flux:callout>
            <div class="flex items-end justify-between">

                <div>
                    <div class="text-sm text-zinc-500">
                        Outstanding
                    </div>

                    <div class="text-2xl font-bold">
                        {{ money($this->outstanding) }}
                    </div>
                </div>

                <flux:button size="sm" wire:click="openPayout">
                    Pay
                </flux:button>

            </div>
        </flux:callout>

    </div>

    {{-- TAB --}}
    <x-ui.tabs :default="$tab">

        <x-slot:tabs>

            <x-ui.tabs.tab name="overview">
                Overview
            </x-ui.tabs.tab>

            <x-ui.tabs.tab name="vouchers">
                Vouchers
            </x-ui.tabs.tab>

            <x-ui.tabs.tab name="commissions">
                Commissions
            </x-ui.tabs.tab>

        </x-slot:tabs>

        <x-ui.tabs.panel name="overview">

            <flux:card>

                <form wire:submit="updateReseller" class="space-y-4">

                    <flux:input label="Name" wire:model="form.name" />

                    <flux:input label="Username" wire:model="form.username" />

                    <div class="flex justify-end">

                        <flux:button type="submit">
                            Save
                        </flux:button>

                    </div>

                </form>

            </flux:card>

        </x-ui.tabs.panel>

        <x-ui.tabs.panel name="vouchers">

            <flux:card>

                <flux:table>

                    <flux:table.columns>
                        <flux:table.column>Username</flux:table.column>
                        <flux:table.column>Profile</flux:table.column>
                        <flux:table.column>Price</flux:table.column>
                        <flux:table.column>Used At</flux:table.column>
                        <flux:table.column>Status</flux:table.column>
                    </flux:table.columns>

                    <flux:table.rows>

                        @forelse ($vouchers as $voucher)
                            <flux:table.row>

                                <flux:table.cell>
                                    {{ $voucher->username }}
                                </flux:table.cell>

                                <flux:table.cell>
                                    {{ $voucher->profile?->name }}
                                </flux:table.cell>

                                <flux:table.cell>
                                    {{ money($voucher->sale_price) }}
                                </flux:table.cell>

                                <flux:table.cell>
                                    {{ dateTime($voucher->used_at) }}
                                </flux:table.cell>

                                <flux:table.cell>

                                    @php
                                        $color = match ($voucher->status) {
                                            'used' => 'green',
                                            'expired' => 'red',
                                            default => 'zinc',
                                        };
                                    @endphp

                                    <flux:badge size="sm" color="{{ $color }}">
                                        {{ ucfirst($voucher->status) }}
                                    </flux:badge>

                                </flux:table.cell>

                            </flux:table.row>

                        @empty

                            <flux:table.row>
                                <flux:table.cell colspan="5" class="text-center">
                                    No vouchers found.
                                </flux:table.cell>
                            </flux:table.row>
                        @endforelse

                    </flux:table.rows>

                </flux:table>

                {{ $vouchers->links() }}

            </flux:card>

        </x-ui.tabs.panel>

        <x-ui.tabs.panel name="commissions">

            <flux:card>

                <flux:table>

                    <flux:table.columns>
                        <flux:table.column>Date</flux:table.column>
                        <flux:table.column>Type</flux:table.column>
                        <flux:table.column>Amount</flux:table.column>
                    </flux:table.columns>

                    <flux:table.rows>

                        @forelse ($commissions as $commission)
                            <flux:table.row>

                                <flux:table.cell>
                                    {{ $commission->created_at->format('d M Y H:i') }}
                                </flux:table.cell>

                                <flux:table.cell>

                                    @php
                                        $color = match ($commission->type) {
                                            'earn' => 'green',
                                            'payout' => 'red',
                                            default => 'zinc',
                                        };
                                    @endphp

                                    <flux:badge size="sm" color="{{ $color }}">
                                        {{ ucfirst($commission->type) }}
                                    </flux:badge>

                                </flux:table.cell>

                                <flux:table.cell>
                                    {{ money($commission->amount) }}
                                </flux:table.cell>

                            </flux:table.row>

                        @empty

                            <flux:table.row>
                                <flux:table.cell colspan="3" class="text-center">
                                    No commissions found.
                                </flux:table.cell>
                            </flux:table.row>
                        @endforelse

                    </flux:table.rows>

                </flux:table>

                {{ $commissions->links() }}

            </flux:card>

        </x-ui.tabs.panel>

    </x-ui.tabs>

    <flux:modal wire:model="showPayoutModal" class="w-sm">

        <div class="space-y-4">

            <flux:heading size="lg">
                Pay Commission
            </flux:heading>

            <flux:callout>
                Outstanding:
                <strong>
                    {{ money($this->outstanding) }}
                </strong>
            </flux:callout>

            <flux:input label="Amount" type="number" wire:model="payoutAmount" />

            <flux:textarea label="Notes" wire:model="payoutNotes" />

            <div class="flex justify-end gap-2">

                <flux:button variant="ghost" wire:click="$set('showPayoutModal', false)">
                    Cancel
                </flux:button>

                <flux:button wire:click="submitPayout">
                    Pay
                </flux:button>

            </div>

        </div>

    </flux:modal>

</x-container>
