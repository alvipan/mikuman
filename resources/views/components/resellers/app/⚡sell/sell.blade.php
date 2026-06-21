<div class="space-y-4">

    <flux:callout>
        <flux:callout.heading class="text-zinc-400">Saldo</flux:callout.heading>
        <flux:callout.text class="text-xl font-bold">
            Rp {{ number_format(auth()->user()->balance ?? 0) }}
        </flux:callout.text>
    </flux:callout>

    <flux:card class="space-y-4">

        @if ($checkout && $this->selectedProfile)

            <flux:heading size="lg">
                Detail Pembelian
            </flux:heading>

            <div class="space-y-1 text-sm">
                <div class="flex justify-between">
                    <span>Paket</span>
                    <span>{{ $this->selectedProfile->name }}</span>
                </div>

                <div class="flex justify-between">
                    <span>Harga</span>
                    <span>Rp {{ number_format($this->selectedProfile->sale_price) }}</span>
                </div>

                <div class="flex justify-between">
                    <span>Jumlah</span>
                    <span>{{ $quantity }}</span>
                </div>

                <flux:separator />

                <div class="flex justify-between font-bold">
                    <span>Subtotal</span>
                    <span>Rp {{ number_format($this->total) }}</span>
                </div>
            </div>

            <div class="space-y-2">
                <flux:button wire:click="generate" wire:loading.attr="disabled" class="w-full">
                    Generate Voucher
                </flux:button>

                <flux:button wire:click="$set('checkout', false)" variant="primary" class="w-full">
                    Kembali
                </flux:button>
            </div>
        @else
            {{-- Router --}}
            @if (auth()->user()->is_admin)
                <flux:select label="Router" wire:model.live="router_id">
                    <option value="">Pilih Router</option>
                    @foreach ($routers as $router)
                        <option value="{{ $router->id }}">{{ $router->name }}</option>
                    @endforeach
                </flux:select>
            @endif

            {{-- Paket --}}
            <flux:select label="Paket" wire:model.live="profile_id" :disabled="!$router_id">
                <option value="">Pilih Paket</option>
                @forelse ($profiles as $profile)
                    <option value="{{ $profile->id }}">{{ $profile->name }}</option>
                @empty
                    <option disabled>Tidak ada paket</option>
                @endforelse
            </flux:select>

            <flux:input type="number" label="Jumlah" min="1" wire:model.live.debounce.300ms="quantity" />

            <flux:button wire:click="$set('checkout', true)" :disabled="!$profile_id || !$quantity" class="w-full">
                Checkout
            </flux:button>
        @endif
    </flux:card>

    {{-- MODAL --}}
    <flux:modal name="result-modal" class="w-sm">
        <div class="space-y-6">
            <div class="flex flex-col items-center text-center">
                <flux:icon.check-circle color="green" class="mb-3 size-12" />
                <h3 class="text-xl font-bold">Berhasil</h3>
                <p class="text-zinc-500">Voucher berhasil dibuat</p>
            </div>

            <div class="max-h-60 space-y-2 overflow-y-auto">
                {{-- Gunakan $vouchers dari state Livewire --}}
                @foreach ($vouchers as $voucher)
                    <div
                        class="rounded-lg border border-zinc-200 px-3 py-2 text-center font-mono text-lg dark:border-zinc-700">
                        {{ $voucher["name"] ?? $voucher->name }}
                    </div>
                @endforeach
            </div>

            {{-- Gunakan pembungkus Close bawaan Flux --}}
            <flux:modal.close>
                <flux:button class="w-full">Tutup</flux:button>
            </flux:modal.close>
        </div>
    </flux:modal>
</div>
