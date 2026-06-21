<div class="space-y-6">

    <flux:heading size="lg">
        Hotspot
    </flux:heading>

    <x-ui.tabs default="packages">

        <x-slot:tabs>

            <x-ui.tabs.tab name="packages">
                Packages
            </x-ui.tabs.tab>

            <x-ui.tabs.tab name="vouchers">
                Vouchers
            </x-ui.tabs.tab>

            <x-ui.tabs.tab name="active">
                Active
            </x-ui.tabs.tab>

        </x-slot:tabs>

        <x-ui.tabs.panel name="packages">
            <livewire:pages::hotspot.packages lazy />
        </x-ui.tabs.panel>

        <x-ui.tabs.panel name="vouchers">
            <livewire:pages::hotspot.vouchers lazy />
        </x-ui.tabs.panel>

        <x-ui.tabs.panel name="active">
            <livewire:pages::hotspot.active-users lazy />
        </x-ui.tabs.panel>

    </x-ui.tabs>

</div>
