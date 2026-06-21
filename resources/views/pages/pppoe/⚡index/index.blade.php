<div class="space-y-6">

    <flux:heading size="lg">
        PPPoE
    </flux:heading>

    <x-ui.tabs default="packages">

        <x-slot:tabs>

            <x-ui.tabs.tab name="customers">
                Customers
            </x-ui.tabs.tab>

            <x-ui.tabs.tab name="packages">
                Packages
            </x-ui.tabs.tab>

            <x-ui.tabs.tab name="secrets">
                Secrets
            </x-ui.tabs.tab>

            <x-ui.tabs.tab name="active">
                Active
            </x-ui.tabs.tab>

        </x-slot:tabs>

        <x-ui.tabs.panel name="customers">
            <livewire:pppoe.customers lazy />
        </x-ui.tabs.panel>

        <x-ui.tabs.panel name="packages">
            <livewire:pppoe.packages lazy />
        </x-ui.tabs.panel>

        <x-ui.tabs.panel name="secrets">
            <livewire:pppoe.secrets lazy />
        </x-ui.tabs.panel>

        <x-ui.tabs.panel name="active">
            <livewire:pppoe.active lazy />
        </x-ui.tabs.panel>

    </x-ui.tabs>

</div>
