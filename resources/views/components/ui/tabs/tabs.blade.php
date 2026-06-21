<div x-data="{ tab: @entangle('tab') }">

    <div class="mb-4 w-full overflow-auto border-b border-zinc-700">
        <nav class="flex gap-2">
            {{ $tabs }}
        </nav>
    </div>

    <div>
        {{ $slot }}
    </div>

</div>
