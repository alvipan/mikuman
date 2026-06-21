<flux:sidebar sticky collapsible="mobile"
    class="border-r border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
    <flux:sidebar.header>
        <flux:sidebar.brand href="#" logo="https://fluxui.dev/img/demo/logo.png"
            logo:dark="https://fluxui.dev/img/demo/dark-mode-logo.png" name="Mikuman" />
        <flux:sidebar.collapse class="lg:hidden" />
    </flux:sidebar.header>

    <flux:sidebar.nav>
        <flux:sidebar.item icon="home" href="/dashboard" wire:navigate>Dashboard</flux:sidebar.item>
        <flux:sidebar.item icon="server" href="/routers" wire:navigate>Router</flux:sidebar.item>
        <flux:sidebar.item icon="user-group" href="/resellers" wire:navigate
            :current="request()->routeIs('resellers.*')">
            Resellers
        </flux:sidebar.item>
        <flux:sidebar.item icon="wifi" href="/hotspot" wire:navigate>Hotspot</flux:sidebar.item>
        <flux:sidebar.item icon="identification" href="/pppoe" wire:navigate :current="request()->routeIs('pppoe.*')">
            PPPoE
        </flux:sidebar.item>
        <flux:sidebar.item icon="presentation-chart-line" href="/reports" :current="request()->routeIs('reports.*')"
            wire:navigate>
            Reports
        </flux:sidebar.item>
    </flux:sidebar.nav>

    <flux:sidebar.spacer />

    <flux:sidebar.nav>
        <flux:sidebar.item icon="cog-6-tooth" href="/settings" wire:navigate>Settings</flux:sidebar.item>
        <flux:sidebar.item icon="information-circle" href="#">Help</flux:sidebar.item>
    </flux:sidebar.nav>

    <flux:dropdown position="top" align="start" class="max-lg:hidden">
        <flux:sidebar.profile :name="auth()->user()->name" />
        <flux:menu>
            <flux:menu.item icon="arrow-right-start-on-rectangle" href="/logout" wire:navigate>Logout</flux:menu.item>
        </flux:menu>
    </flux:dropdown>
</flux:sidebar>
