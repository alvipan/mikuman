@props([
    'id' => '',
    'icon' => '',
    'label' => '',
    'expandable' => true,
    'expanded' => false,
])

<li @class(['accordion-item', 'active' => $expanded])">
    <a 
        aria-expanded="{{ $expanded }}" 
        style="font-size: var(--text-md)"
        @class([
            'accordion-toggle',
            'flex',
            'items-center',
            'gap-3',
            'px-0',
            'py-1',
            'text-base-content/70',
            'hover:text-base-content' => !$expanded,
            'text-primary' => $expanded
        ])>
        <span class="icon-[{{ $icon }}] size-5"></span>
        {{ $label }}
        <span class="ms-auto icon-[tabler--chevron-right] accordion-item-active:rotate-90 size-5 shrink-0 transition-transform duration-300"></span>
    </a>

    <ul class="ps-5 border-s-2 border-base-content/30 ms-2.5 mt-2 space-y-1 accordion-content {{ $expanded ? '' : 'hidden' }} w-full overflow-hidden transition-[height] duration-300" aria-labelledby="delivery-arrow-right" role="region">
        {{ $slot }}
    </ul>
</li>