<aside class="aside-scroll top-16 z-20 h-[calc(100vh-5rem)] overflow-y-auto px-8 py-6 max-md:hidden lg:sticky">
    <nav class="text-sm capitalize">
        <ul class="space-y-2.5">
            <li>
                <a 
                    @class([
                        'flex',
                        'items-center',
                        'gap-3',
                        'py-1',
                        'text-primary' => $menu == 'routers'
                    ]) href="/routers">
                    <span class="icon-[tabler--router] size-5"></span>
                    Router List
                </a>
            </li>
            <li>
                <a class="flex items-center gap-3 py-1" href="/settings">
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