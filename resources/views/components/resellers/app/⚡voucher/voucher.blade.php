<div class="space-y-4">

    <flux:heading size="lg" class="text-zinc-400">
        Voucher Terjual
    </flux:heading>

    @forelse ($this->vouchers as $item)
        @php
            $color = match ($item->sale_price) {
                5000 => "emerald",
                10000 => "blue",
                20000 => "amber",
                50000 => "green",
                80000 => "rose",
                100000 => "red",
                default => "gray",
            };
        @endphp

        <flux:callout color="{{ $color }}" class="space-y-4">

            <div class="flex items-center justify-between">

                <div>
                    <div class="font-bold">
                        Rp {{ number_format($item->sale_price) }}
                    </div>

                    <div class="text-xs text-gray-400">
                        {{ $item->created_at->format("d M Y H:i") }}
                    </div>
                </div>

                <flux:avatar icon="ticket" color="{{ $color }}" />

            </div>

            <flux:callout>
                <div class="text-center font-mono text-lg font-bold">
                    {{ $item->voucher_name }}
                </div>
            </flux:callout>

        </flux:callout>
    @empty
        <flux:callout class="text-center">
            Belum ada data voucher
        </flux:callout>
    @endforelse

    <script src="https://cdn.jsdelivr.net/npm/qrcodejs/qrcode.min.js"></script>

</div>
