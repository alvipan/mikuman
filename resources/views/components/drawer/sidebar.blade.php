<aside id="sidenav" class="overlay [--auto-close:sm] sm:shadow-none overlay-open:translate-x-0 drawer drawer-start hidden max-w-64 sm:relative sm:z-0 sm:flex sm:translate-x-0" role="dialog" tabindex="-1">
    <div class="drawer-header w-full flex items-center justify-between gap-3 sm:hidden">
        <h3 class="drawer-title text-xl font-semibold flex items-center gap-3">
            {{config('app.name')}}
        </h3>
        <span class="text-sm text-base-content/60 me-auto">{{'v'.config('app.version')}}</span>
        <div class="mx">
            <!-- Toggle Button -->
            <button type="button" class="btn btn-circle btn-sm btn-text" aria-haspopup="dialog" aria-expanded="false" aria-controls="sidenav" aria-label="Minify navigation" data-overlay="#sidenav">
                <span class="icon-[tabler--x] size-5"></span>
                <span class="sr-only">Navigation Toggle</span>
            </button>
            <!-- End Toggle Button -->
        </div>
    </div>
    <div class="drawer-body px-0">
        <x-navlist>
            <x-navlist.item :href="route('dashboard')" :current="request()->routeIs('dashboard')" :icon="__('tabler--layout-dashboard')">Dashboard</x-navlist.item>
            <x-navlist.group :expanded="request()->routeIs('hotspot.*')" :label="__('Hotspot')" :icon="__('tabler--wifi')">
                <x-navlist.item :href="route('hotspot.profiles')" :current="request()->routeIs('hotspot.profiles')">Profiles</x-navlist.item>
                <x-navlist.item :href="route('hotspot.users')" :current="request()->routeIs('hotspot.users')">Users</x-navlist.item>
                <x-navlist.item :href="route('hotspot.active')" :current="request()->routeIs('hotspot.active')">Active</x-navlist.item>
            </x-navlist.group>
            <x-navlist.group :expanded="request()->routeIs('pppoe.*')" :label="__('PPPoE')" :icon="__('tabler--devices-pc')">
                <x-navlist.item :href="route('pppoe.profiles')" :current="request()->routeIs('pppoe.profiles')">Profiles</x-navlist.item>
                <x-navlist.item :href="route('pppoe.secrets')" :current="request()->routeIs('pppoe.secrets')">Secrets</x-navlist.item>
                <x-navlist.item :href="route('pppoe.active')" :current="request()->routeIs('pppoe.active')">Active</x-navlist.item>
            </x-navlist.group>
            <x-navlist.item :href="route('report')" :current="request()->routeIs('report')" :icon="__('tabler--coins')">Report</x-navlist.item>
            <x-navlist.item :href="route('logs')" :current="request()->routeIs('logs')" :icon="__('tabler--logs')">Logs</x-navlist.item>
            <x-navlist.item :href="route('about')" :current="request()->routeIs('about')" :icon="__('tabler--help')">About</x-navlist.item>
            <x-navlist.item :href="route('logout')" :icon="__('tabler--logout')">Logout</x-navlist.item>
        </x-navlist>
    </div>

    <div class="drawer-footer">
        <a href="https://sociabuzz.com/alvipan/tribe" target="_blank" class="btn btn-primary w-full">
            <span class="icon-[tabler--moneybag-heart] size-5"></span>
            Donate
        </a>
    </div>
</aside>