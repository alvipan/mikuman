<main class="flex-1 space-y-4 overflow-y-auto">

    {{-- Header --}}
    @if (isset($title) || isset($filters) || isset($actions))

        <div class="space-y-4">

            {{-- Title --}}
            @isset($title)
                <div>
                    {{ $title }}
                </div>
            @endisset

            {{-- Filters + Actions --}}
            @if (isset($filters) || isset($actions))
                <div class="flex justify-between gap-3">

                    <div class="flex items-center gap-2">
                        {{ $filters ?? "" }}
                    </div>

                    <div class="flex items-center gap-2">
                        {{ $actions ?? "" }}
                    </div>

                </div>
            @endif

        </div>

    @endif

    {{-- Content --}}
    <div>
        {{ $slot }}
    </div>

</main>
