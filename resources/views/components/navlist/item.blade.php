@props([
    'current' => false,
    'href' => '#',
    'icon' => null,
])
<li>
    <a {{ $attributes }} href="{{ $href }}" 
        @class([
            'flex',
            'items-center',
            'gap-3',
            'py-1',
            'text-base-content/70',
            'hover:text-base-content' => !$current,
            'text-primary' => $current
        ])>

        @if($icon != null)
        <span class="icon-[{{ $icon }}] size-5"></span>
        @endif

        {{ $slot }}
    </a>
</li>