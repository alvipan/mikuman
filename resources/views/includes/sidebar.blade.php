<aside id="sidenav" class="overlay [--auto-close:sm] [--overlay-backdrop:false] sm:shadow-none overlay-open:translate-x-0 drawer drawer-start hidden max-w-64 sm:relative sm:z-0 sm:flex sm:translate-x-0" role="dialog" tabindex="-1">
    <nav class="capitalize py-4 px-6 sm:px-8">
        <ul class="space-y-2.5 accordion mb-auto">
            <li class="sm:hidden">
                <div class="flex items-center justify-between py-1">
                    <a href="/" class="flex items-center gap-3 text-xl font-semibold">
                        <span class="icon-[tabler--comet]"></span>
                        Mikuman
                    </a>
                    <button type="button" class="btn btn-text btn-circle -me-1.5 btn-sm" aria-label="Close" data-overlay="#sidenav">
                        <span class="icon-[tabler--x] size-4"></span>
                    </button>
                </div>
            </li>
            <li>
                <a @class([
                        'flex',
                        'items-center',
                        'gap-3',
                        'py-1',
                        'text-base-content/70',
                        'hover:text-base-content' => $menu != 'dashboard',
                        'text-primary' => $menu == 'dashboard'
                    ]) href="/dashboard">
                    <span class="icon-[tabler--layout-dashboard] size-5"></span>
                    Dashboard
                </a>
            </li>
            <li class="accordion-item {{ $menu == 'hotspot' ? 'active' : '' }}" id="hotspot-accordion">
                <a @class([
                        'accordion-toggle',
                        'flex',
                        'items-center',
                        'gap-3',
                        'px-0',
                        'py-1',
                        'font-normal',
                        'text-base-content/70',
                        'hover:text-base-content' => $menu != 'hotspot',
                        'text-primary' => $menu == 'hotspot'
                    ])
                    style="font-size: var(--text-md)"
                    aria-expanded="{{($menu == 'hotspot')}}" 
                    aria-controls="hotspot-accordion-collapse">
                    <span class="icon-[tabler--wifi] size-5"></span>
                    Hotspot
                    <span class="icon-[tabler--chevron-right] accordion-item-active:rotate-90 size-4 shrink-0 transition-transform duration-300 ms-auto" ></span>
                </a>
                <ul id="hotspot-accordion-collapse" class="border-s-2 border-base-content/30 ms-2.5 mt-2 space-y-1 accordion-content {{$menu == 'hotspot' ? '' : 'hidden' }} w-full overflow-hidden transition-[height] duration-300" aria-labelledby="delivery-arrow-right" role="region">
                    <li>
                        <a @class([
                                'flex',
                                'ps-5',
                                'py-1',
                                'border-s-2',
                                'text-base-content/70',
                                'border-primary' => $submenu == 'hotspot-profiles',
                                'border-transparent' => $submenu != 'hotspot-profiles',
                                'hover:border-base-content/60' => $submenu != 'hotspot-profiles',
                                'hover:text-base-content' => $submenu != 'hotspot-profiles',
                                'text-primary' => $submenu == 'hotspot-profiles'
                            ]) href="/hotspot/profiles">
                            Profiles
                        </a>
                    </li>
                    <li>
                        <a @class([
                                'flex',
                                'ps-5',
                                'py-1',
                                'border-s-2',
                                'text-base-content/70',
                                'border-primary' => $submenu == 'hotspot-users',
                                'border-transparent' => $submenu != 'hotspot-users',
                                'hover:border-base-content/60' => $submenu != 'hotspot-users',
                                'hover:text-base-content' => $submenu != 'hotspot-users',
                                'text-primary' => $submenu == 'hotspot-users'
                            ]) href="/hotspot/users">
                            Users
                        </a>
                    </li>
                    <li>
                        <a @class([
                                'flex',
                                'ps-5',
                                'py-1',
                                'border-s-2',
                                'text-base-content/70',
                                'border-primary' => $submenu == 'hotspot-active',
                                'border-transparent' => $submenu != 'hotspot-active',
                                'hover:border-base-content/60' => $submenu != 'hotspot-active',
                                'hover:text-base-content' => $submenu != 'hotspot-active',
                                'text-primary' => $submenu == 'hotspot-active'
                            ]) href="/hotspot/active">
                            Active
                        </a>
                    </li>
                </ul>
            </li>
            <li class="accordion-item {{ $menu == 'pppoe' ? 'active' : '' }}" id="pppoe-accordion">
                <button @class([
                        'accordion-toggle',
                        'flex',
                        'items-center',
                        'gap-3',
                        'px-0',
                        'py-1',
                        'font-normal',
                        'text-base-content/70',
                        'hover:text-base-content' => $menu != 'pppoe',
                        'text-primary' => $menu == 'pppoe'
                    ])
                    style="font-size: var(--text-md)"
                    aria-expanded="{{($menu == 'pppoe')}}" 
                    aria-controls="pppoe-accordion-collapse">
                    <span class="icon-[tabler--devices-pc] size-5"></span>
                    PPPoE
                    <span class="icon-[tabler--chevron-right] accordion-item-active:rotate-90 size-4 shrink-0 transition-transform duration-300 ms-auto" ></span>
                </button>
                <ul id="pppoe-accordion-collapse" class="border-s-2 border-base-content/30 ms-2.5 mt-2 space-y-1 accordion-content {{$menu == 'pppoe' ? '' : 'hidden' }} w-full overflow-hidden transition-[height] duration-300" aria-labelledby="delivery-arrow-right" role="region">
                    <li>
                        <a @class([
                                'flex',
                                'ps-5',
                                'py-1',
                                'border-s-2',
                                'text-base-content/70',
                                'border-primary' => $submenu == 'pppoe-profiles',
                                'border-transparent' => $submenu != 'pppoe-profiles',
                                'hover:border-base-content/60' => $submenu != 'pppoe-profiles',
                                'hover:text-base-content' => $submenu != 'pppoe-profiles',
                                'text-primary' => $submenu == 'pppoe-profiles'
                            ]) href="/pppoe/profiles">
                            Profiles
                        </a>
                    </li>
                    <li>
                        <a @class([
                                'flex',
                                'ps-5',
                                'py-1',
                                'border-s-2',
                                'text-base-content/70',
                                'border-primary' => $submenu == 'pppoe-users',
                                'border-transparent' => $submenu != 'pppoe-users',
                                'hover:border-base-content/60' => $submenu != 'pppoe-users',
                                'hover:text-base-content' => $submenu != 'pppoe-users',
                                'text-primary' => $submenu == 'pppoe-users'
                            ]) href="/pppoe/users">
                            Users
                        </a>
                    </li>
                    <li>
                        <a @class([
                                'flex',
                                'ps-5',
                                'py-1',
                                'border-s-2',
                                'text-base-content/70',
                                'border-primary' => $submenu == 'pppoe-active',
                                'border-transparent' => $submenu != 'pppoe-active',
                                'hover:border-base-content/60' => $submenu != 'pppoe-active',
                                'hover:text-base-content' => $submenu != 'pppoe-active',
                                'text-primary' => $submenu == 'pppoe-active'
                            ]) href="/pppoe/active">
                            Active
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <a @class([
                        'flex',
                        'items-center',
                        'gap-3',
                        'py-1',
                        'text-base-content/70',
                        'hover:text-base-content' => $menu != 'report',
                        'text-primary' => $menu == 'report'
                    ]) href="/report">
                    <span class="icon-[tabler--coins] size-5"></span>
                    Report
                </a>
            </li>
            <li>
                <a @class([
                        'flex',
                        'items-center',
                        'gap-3',
                        'py-1',
                        'text-base-content/70',
                        'hover:text-base-content' => $menu != 'logs',
                        'text-primary' => $menu == 'logs'
                    ]) href="/logs">
                    <span class="icon-[tabler--logs] size-5"></span>
                    Logs
                </a>
            </li>
            <li>
                <a @class([
                        'flex',
                        'items-center',
                        'gap-3',
                        'py-1',
                        'text-base-content/70',
                        'hover:text-base-content' => $menu != 'about',
                        'text-primary' => $menu == 'about'
                    ]) href="/about">
                    <span class="icon-[tabler--help] size-5"></span>
                    About
                </a>
            </li>
            <li>
                <a @class([
                        'flex',
                        'items-center',
                        'gap-3',
                        'py-1',
                        'text-base-content/70',
                        'hover:text-base-content'
                    ]) href="/logout">
                    <span class="icon-[tabler--logout] size-5"></span>
                    Logout
                </a>
            </li>
        </ul>
        
    </nav>
    <div class="drawer-footer">
        <a href="https://sociabuzz.com/alvipan/tribe" target="_blank" class="btn btn-primary w-full">
            <span class="icon-[tabler--moneybag-heart] size-5"></span>
            Donate
        </a>
    </div>
</aside>