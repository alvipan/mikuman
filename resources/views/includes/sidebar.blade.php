<aside class="aside-scroll top-16 z-20 h-[calc(100vh-5rem)] overflow-y-auto ps-8 py-4 max-md:hidden md:sticky">
    <nav class="capitalize">
        <ul class="space-y-2.5">
            <li>
                <a @class([
                        'flex',
                        'items-center',
                        'gap-3',
                        'py-1',
                        'text-primary' => $menu == 'dashboard'
                    ]) href="/routers">
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
                        'text-primary' => $menu == 'hotspot'
                    ])
                    data-collapse="#hotspot-collapse">
                    <span class="icon-[tabler--wifi] size-5"></span>
                    Hotspot
                    <span class="ms-auto icon-[tabler--chevron-right] collapse-open:rotate-90 size-4"></span>
                </a>
                <ul id="hotspot-collapse" class="border-s-2 ms-2.5 ps-5 mt-2 space-y-2.5 collapse hidden overflow-hidden transition-[height] duration-300" aria-labelledby="basic-collapse">
                    <li>Prrofile</li>
                    <li>Users</li>
                    <li>Active</li>
                </ul>
            </li>
            <li>
                <a @class([
                        'flex',
                        'items-center',
                        'gap-3',
                        'py-1',
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
                        'text-primary' => $menu == 'income'
                    ]) href="/routers">
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
                        'text-primary' => $menu == 'settings'
                    ]) href="/settings">
                    <span class="icon-[tabler--settings] size-5"></span>
                    Settings
                </a>
            </li>
            <li>
                <a class="flex items-center gap-3 py-1" href="/logout">
                    <span class="icon-[tabler--logout] size-5"></span>
                    Logout
                </a>
            </li>
        </ul>
    </nav>
</aside>