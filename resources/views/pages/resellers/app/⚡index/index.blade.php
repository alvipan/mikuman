<div x-data="{
    tab: 'dashboard',
    loaded: {
        dashboard: true,
        vouchers: false,
        sell: false,
        transactions: false,
        account: false,
    },
    setTab(name) {
        this.tab = name
        this.loaded[name] = true
    }
}">

    {{-- CONTENT --}}
    <div class="space-y-4">

        {{-- DASHBOARD --}}
        <div x-show="tab === 'dashboard'">
            <livewire:resellers.app.dashboard wire:key="tab-dashboard" />
        </div>

        {{-- VOUCHERS --}}
        <div x-show="tab === 'vouchers'">
            <template x-if="loaded.vouchers">
                <div wire:init>
                    <livewire:resellers.app.voucher wire:key="tab-vouchers" />
                </div>
            </template>

            {{-- Skeleton --}}
            <div x-show="!loaded.vouchers" class="animate-pulse space-y-2">
                <div class="h-4 rounded bg-zinc-700"></div>
                <div class="h-4 rounded bg-zinc-700"></div>
                <div class="h-4 rounded bg-zinc-700"></div>
            </div>
        </div>

        {{-- SELL --}}
        <div x-show="tab === 'sell'">
            <template x-if="loaded.sell">
                <div wire:init>
                    <livewire:resellers.app.sell wire:key="tab-sell" />
                </div>
            </template>

            <div x-show="!loaded.sell" class="h-20 animate-pulse rounded bg-zinc-700"></div>
        </div>

        {{-- TRANSACTIONS --}}
        <div x-show="tab === 'transactions'">
            <template x-if="loaded.transactions">
                <div wire:init>
                    <livewire:resellers.app.transaction wire:key="tab-transactions" />
                </div>
            </template>

            <div x-show="!loaded.transactions" class="animate-pulse space-y-2">
                <div class="h-4 rounded bg-zinc-700"></div>
                <div class="h-4 rounded bg-zinc-700"></div>
            </div>
        </div>

        {{-- ACCOUNT --}}
        <div x-show="tab === 'account'">
            <template x-if="loaded.account">
                <div wire:init>
                    <livewire:resellers.app.account wire:key="tab-account" />
                </div>
            </template>

            <div x-show="!loaded.account" class="h-10 animate-pulse rounded bg-zinc-700"></div>
        </div>

    </div>

    {{-- BOTTOM NAV --}}
    <div class="fixed bottom-0 left-0 right-0 z-50 bg-zinc-900 shadow backdrop-blur">
        <div class="grid grid-cols-5 items-center">

            <button @click="setTab('dashboard')" :class="tab === 'dashboard' ? 'text-blue-500' : 'text-zinc-400'"
                class="flex flex-col items-center">
                <flux:icon.rectangle-group />
                <span class="text-xs">Dasbor</span>
            </button>

            <button @click="setTab('vouchers')" :class="tab === 'vouchers' ? 'text-blue-500' : 'text-zinc-400'"
                class="flex flex-col items-center">
                <flux:icon.ticket />
                <span class="text-xs">Voucher</span>
            </button>

            <button @click="setTab('sell')" class="relative -top-4 mx-auto">
                <flux:avatar icon="plus" circle :color="'blue'" size="xl" />
            </button>

            <button @click="setTab('transactions')" :class="tab === 'transactions' ? 'text-blue-500' : 'text-zinc-400'"
                class="flex flex-col items-center">
                <flux:icon.banknotes />
                <span class="text-xs">Transaksi</span>
            </button>

            <button @click="setTab('account')" :class="tab === 'account' ? 'text-blue-500' : 'text-zinc-400'"
                class="flex flex-col items-center">
                <flux:icon.user />
                <span class="text-xs">Akun</span>
            </button>

        </div>
    </div>

</div>
