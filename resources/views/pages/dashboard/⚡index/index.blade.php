<x-container>

    <div class="space-y-6">

        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

            <flux:heading size="lg">Dashboard</flux:heading>

            <div class="flex gap-2">

                <flux:select wire:model.live="period" size="sm">

                    <flux:select.option value="today">
                        Today
                    </flux:select.option>

                    <flux:select.option value="yesterday">
                        Yesterday
                    </flux:select.option>

                    <flux:select.option value="week">
                        Week
                    </flux:select.option>

                    <flux:select.option value="last_week">
                        Last Week
                    </flux:select.option>

                    <flux:select.option value="month">
                        Month
                    </flux:select.option>

                    <flux:select.option value="last_month">
                        Last Month
                    </flux:select.option>

                </flux:select>
            </div>

        </div>

        <div class="space-y-4">

            <flux:heading size="lg">
                Statistics
            </flux:heading>

            <div class="grid gap-4 lg:grid-cols-3">

                {{-- Router --}}
                <flux:card class="space-y-3">

                    <div class="font-semibold">
                        Router
                    </div>

                    <div class="space-y-2 text-sm">

                        <div class="flex justify-between">
                            <span>Total</span>
                            <span>{{ $stats['router']['total'] ?? 0 }}</span>
                        </div>

                        <div class="flex justify-between">
                            <span>Ready</span>
                            <span>{{ $stats['router']['ready'] ?? 0 }}</span>
                        </div>

                        <div class="flex justify-between">
                            <span>Not Setup</span>
                            <span>{{ $stats['router']['not_setup'] ?? 0 }}</span>
                        </div>

                        <div class="flex justify-between">
                            <span>Offline</span>
                            <span>{{ $stats['router']['offline'] ?? 0 }}</span>
                        </div>

                    </div>

                </flux:card>

                {{-- Voucher --}}
                <flux:card class="space-y-3">

                    <div class="font-semibold">
                        Voucher
                    </div>

                    <div class="space-y-2 text-sm">

                        <div class="flex justify-between">
                            <span>Unused</span>
                            <span>{{ $stats['voucher']['unused'] ?? 0 }}</span>
                        </div>

                        <div class="flex justify-between">
                            <span>Used</span>
                            <span>{{ $stats['voucher']['used'] ?? 0 }}</span>
                        </div>

                        <div class="flex justify-between">
                            <span>Expired</span>
                            <span>{{ $stats['voucher']['expired'] ?? 0 }}</span>
                        </div>

                    </div>

                </flux:card>

                {{-- PPPoE --}}
                <flux:card class="space-y-3">

                    <div class="font-semibold">
                        PPPoE
                    </div>

                    <div class="space-y-2 text-sm">

                        <div class="flex justify-between">
                            <span>Active</span>
                            <span>{{ $stats['pppoe']['active'] ?? 0 }}</span>
                        </div>

                        <div class="flex justify-between">
                            <span>Disabled</span>
                            <span>{{ $stats['pppoe']['disabled'] ?? 0 }}</span>
                        </div>

                        <div class="flex justify-between">
                            <span>Expired</span>
                            <span>{{ $stats['pppoe']['expired'] ?? 0 }}</span>
                        </div>

                    </div>

                </flux:card>

            </div>

        </div>

        <flux:card class="space-y-4">

            <flux:heading size="lg">
                Revenue
            </flux:heading>

            <div class="grid gap-4 md:grid-cols-3">

                <div>
                    <div class="text-sm text-zinc-400">
                        Hotspot
                    </div>

                    <div class="text-2xl font-bold">
                        {{ money($revenue['hotspot'] ?? 0) }}
                    </div>
                </div>

                <div>
                    <div class="text-sm text-zinc-400">
                        PPPoE
                    </div>

                    <div class="text-2xl font-bold">
                        {{ money($revenue['pppoe'] ?? 0) }}
                    </div>
                </div>

                <div>
                    <div class="text-sm text-zinc-400">
                        Total
                    </div>

                    <div class="text-2xl font-bold">
                        {{ money($revenue['total'] ?? 0) }}
                    </div>
                </div>

            </div>

        </flux:card>

        <flux:card class="space-y-4">

            <div class="flex items-center justify-between">
                <flux:heading size="lg">
                    Transactions
                </flux:heading>
            </div>

            <div class="space-y-3 divide-y divide-zinc-500">

                @forelse ($transactions as $trx)
                    <div class="flex items-center justify-between pb-2">

                        <div class="space-y-1">
                            <div class="text-sm font-medium">
                                {{ $trx['name'] ?? '-' }}
                            </div>

                            <div class="text-xs text-zinc-400">
                                {{ datetime($trx['date']) }}
                            </div>
                        </div>

                        <div class="text-right">
                            <div class="text-sm font-semibold">
                                {{ money($trx['amount']) }}
                            </div>

                            <div class="text-xs">
                                @if ($trx['type'] === 'hotspot')
                                    <span class="text-blue-500">Hotspot</span>
                                @else
                                    <span class="text-green-500">PPPoE</span>
                                @endif
                            </div>
                        </div>

                    </div>
                @empty
                    <div class="text-sm text-zinc-500">
                        Tidak ada transaksi
                    </div>
                @endforelse

            </div>

        </flux:card>

    </div>

</x-container>
