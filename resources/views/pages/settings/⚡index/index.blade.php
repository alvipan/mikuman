<x-container>

    <div class="space-y-4">

        <flux:heading size="lg">
            Settings
        </flux:heading>

        <flux:card class="space-y-4">

            <flux:heading>
                Localization
            </flux:heading>

            <flux:field>
                <flux:label>Currency</flux:label>

                <flux:select wire:model.live="currencyCode">

                    @foreach ($currencies as $code => $symbol)
                        <flux:select.option :value="$code">
                            {{ $code }} ({{ $symbol }})
                        </flux:select.option>
                    @endforeach

                </flux:select>
            </flux:field>

            <flux:field>
                <flux:label>Timezone</flux:label>

                <flux:select wire:model.live="timezone">

                    @foreach (DateTimeZone::listIdentifiers() as $timezone)
                        <flux:select.option :value="$timezone">
                            {{ $timezone }}
                        </flux:select.option>
                    @endforeach

                </flux:select>
            </flux:field>

            <flux:callout variant="secondary">
                <div class="space-y-1 text-sm">
                    <div>
                        Currency Preview:
                        {{ $currencies[$currencyCode] ?? '' }} 123,456
                    </div>

                    <div>
                        Timezone Preview:
                        {{ $this->timezonePreview }}
                    </div>
                </div>
            </flux:callout>

            <flux:separator />

            <div class="flex justify-end">
                <flux:button variant="primary" wire:click="save" wire:loading.attr="disabled">
                    Save Settings
                </flux:button>
            </div>

        </flux:card>

    </div>

</x-container>
