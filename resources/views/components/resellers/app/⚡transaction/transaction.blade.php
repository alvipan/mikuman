<div class="space-y-4">
    <flux:heading size="lg" class="text-zinc-400">
        Riwayat Transaksi
    </flux:heading>

    @forelse ($this->transactions as $tx)
        <flux:callout>

            <div class="flex items-center justify-between">

                <div>

                    <div class="font-medium">
                        {{ $tx->label }}
                    </div>

                    <div class="space-y-1">
                        <p class="text-xs text-gray-500">
                            {{ $tx->created_at->format("d M Y H:i") }}
                        </p>
                        <flux:badge size="sm">
                            {{ ucfirst($tx->type) }}
                        </flux:badge>
                    </div>

                </div>

                <div class="text-right">

                    <div class="{{ $tx->amount > 0 ? "text-green-600" : "text-red-600" }} font-semibold">
                        Rp {{ ($tx->amount > 0 ? "+" : "") . number_format($tx->amount) }}
                    </div>

                </div>

            </div>

        </flux:callout>
    @empty
        <flux:callout class="text-center">
            Belum ada data transaksi
        </flux:callout>
    @endforelse

</div>
