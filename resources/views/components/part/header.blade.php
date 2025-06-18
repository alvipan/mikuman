<header class="sticky top-0 z-50 w-full max-md:shadow">
    <div class="container bg-base-100 mx-auto flex gap-2 h-16 max-w-screen-2xl items-center px-4 md:px-8">
        <button type="button" class="btn btn-sm btn-text btn-outline btn-square lg:hidden me-2" aria-label="Menu" aria-haspopup="dialog" aria-expanded="false" aria-controls="sidenav" data-overlay="#sidenav">
            <span class="icon-[tabler--menu-2] size-5"></span>
        </button>
        <a href="/" class="flex items-center gap-3 text-xl font-semibold">
            <span class="icon-[tabler--comet]"></span>
            Mikuman
        </a>
        <span class="text-sm text-base-content/60 me-auto">v{{env('APP_VERSION')}}</span>
        <div class="flex gap-2 items-center">
            <a href="https://fb.me/alvipan93" target="_blank" class="btn btn-sm btn-text btn-square">
                <span class="icon-[tabler--brand-facebook] size-6"></span>
            </a>
            <a href="https://linkedin.com/in/alvipan" target="_blank" class="btn btn-sm btn-text btn-square">
                <span class="icon-[tabler--brand-linkedin] size-6"></span>
            </a>
            <a href="https://github.com/alvipan/mikuman" target="_blank" class="btn btn-sm btn-text btn-square">
                <span class="icon-[tabler--brand-github] size-6"></span>
            </a>
            <div class="divider divider-horizontal"></div>
            <button type="button" class="btn btn-text btn-sm" aria-haspopup="dialog" aria-expanded="false" aria-controls="overlay-setting" data-overlay="#overlay-setting">
                Settings
            </button>
        </div>
    </div>
</header>