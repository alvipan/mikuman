<aside class="aside-scroll top-16 z-20 h-[calc(100vh-5rem)] overflow-y-auto ps-8 pe-4 py-4 max-md:hidden md:sticky">
    <nav class="capitalize">
        <ul class="space-y-2.5">
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
            <li>
                <a @class([
                        'cursor-pointer',
                        'collapse-toggle',
                        'flex',
                        'items-center',
                        'gap-3',
                        'py-1',
                        'text-base-content/70',
                        'hover:text-base-content' => $menu != 'hotspot',
                        'text-primary' => $menu == 'hotspot',
                        'open' => $menu == 'hotspot'
                    ])
                    aria-expanded="{{($menu == 'hotspot')}}"
                    data-collapse="#hotspot-collapse">
                    <span class="icon-[tabler--wifi] size-5"></span>
                    <span>Hotspot</span>
                    <span class="ms-auto icon-[tabler--chevron-right] collapse-open:rotate-90 size-4"></span>
                </a>
                <ul id="hotspot-collapse" class="border-s-2 border-base-content/30 ms-2.5 mt-2 space-y-1 collapse overflow-hidden transition-[height] duration-300 {{($menu == 'hotspot')?'open':'hidden'}}" aria-labelledby="basic-collapse">
                    <li>
                        <a @class([
                                'flex',
                                'ps-5',
                                'py-1',
                                'border-s-2',
                                'text-base-content/70',
                                'border-primary' => $submenu == 'profile',
                                'border-transparent' => $submenu != 'profile',
                                'hover:border-base-content/60' => $submenu != 'profile',
                                'hover:text-base-content' => $submenu != 'profile',
                                'text-primary' => $submenu == 'profile'
                            ]) href="/hotspot/profile">
                            Profile
                        </a>
                    </li>
                    <li>
                        <a @class([
                                'flex',
                                'ps-5',
                                'py-1',
                                'border-s-2',
                                'text-base-content/70',
                                'border-primary' => $submenu == 'users',
                                'border-transparent' => $submenu != 'users',
                                'hover:border-base-content/60' => $submenu != 'users',
                                'hover:text-base-content' => $submenu != 'users',
                                'text-primary' => $submenu == 'users'
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
                                'border-primary' => $submenu == 'active',
                                'border-transparent' => $submenu != 'active',
                                'hover:border-base-content/60' => $submenu != 'active',
                                'hover:text-base-content' => $submenu != 'active',
                                'text-primary' => $submenu == 'active'
                            ]) href="/hotspot/active">
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
                        'hover:text-base-content' => $menu != 'pppoe',
                        'text-primary' => $menu == 'pppoe'
                    ]) href="/routers">
                    <span class="icon-[tabler--devices-pc] size-5"></span>
                    PPPoE
                </a>
            </li>
            <li>
                <a @class([
                        'flex',
                        'items-center',
                        'gap-3',
                        'py-1',
                        'text-base-content/70',
                        'hover:text-base-content' => $menu != 'income',
                        'text-primary' => $menu == 'income'
                    ]) href="/income">
                    <span class="icon-[tabler--coins] size-5"></span>
                    Income
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
                        'hover:text-base-content' => $menu != 'settings',
                        'text-primary' => $menu == 'settings'
                    ]) href="/settings">
                    <span class="icon-[tabler--settings] size-5"></span>
                    Settings
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
</aside>