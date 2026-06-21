<div class="space-y-6">

    {{-- SALDO CARD --}}
    <div class="grid grid-cols-2 gap-4">
        <flux:callout color="blue">

            <div class="text-sm opacity-80">
                Penjualan
            </div>

            <div class="text-xl font-semibold text-blue-500">
                Rp {{ number_format($this->outstanding) }}
            </div>

        </flux:callout>

        <flux:callout color="green">

            <div class="text-sm opacity-80">
                Komisi
            </div>

            <div class="text-xl font-semibold text-green-500">
                Rp {{ number_format(auth()->user()->commission ?? 0) }}
            </div>

        </flux:callout>
    </div>

    {{-- TRANSAKSI TERBARU --}}
    <div class="space-y-4">

        <div class="flex items-center justify-between">

            <h2 class="text-gray-400">
                Transaksi Terbaru
            </h2>

            <flux:button size="sm" variant="ghost" @click="setTab('transactions')">
                Lihat semua
            </flux:button>

        </div>

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

</div>
