<x-container>

    <x-slot:title>
        <flux:heading size="lg">
            Sell Voucher
        </flux:heading>
    </x-slot:title>

    <flux:card class="flex flex-col gap-4">

        {{-- Router hanya untuk admin --}}
        @if (auth()->user()->is_admin)

            <flux:select label="Router" wire:model.live="router_id">
                <option value="">Select Router</option>

                @foreach ($routers as $router)
                    <option value="{{ $router->id }}">
                        {{ $router->name }}
                    </option>
                @endforeach

            </flux:select>

        @endif

        <flux:select label="Paket" wire:model="profile_id">
            <option value="">Select Package</option>

            @foreach ($profiles as $profile)
                <option value="{{ $profile->id }}">
                    {{ $profile->name }}
                </option>
            @endforeach

        </flux:select>

        <flux:input type="number" label="Jumlah" min="1" wire:model="quantity" />

        <div class="flex justify-end">

            <flux:button wire:click="generate" wire:target="generate" class="max-sm:w-full">
                Generate
            </flux:button>

        </div>

    </flux:card>

    {{-- MODAL RESULT --}}
    <flux:modal wire:model="showResult" class="w-sm">

        <div class="space-y-6">

            <div class="flex flex-col items-center justify-center">
                <flux:icon.check-circle color="green" class="mb-3 size-12" />
                <h3 class="text-xl font-bold">Successfully</h3>
                <p class="text-zinc-500">Voucher successfully generated</p>
            </div>

            <div class="space-y-3">

                @foreach ($vouchers as $voucher)
                    <div class="rounded-lg border border-zinc-700 px-3 py-2 text-center font-mono text-lg">
                        {{ $voucher->name }}
                    </div>
                @endforeach

            </div>

            <div>

                <flux:button class="w-full" wire:click="$set('showResult', false)">
                    Close
                </flux:button>

            </div>

        </div>

    </flux:modal>

</x-container>
