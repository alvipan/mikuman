@props(['name'])

<button type="button" @click="tab = '{{ $name }}'"
    :class="tab === '{{ $name }}'
        ?
        'border-b-2 border-primary dark:border-primary/20' :
        'text-gray-400'"
    class="p-3 text-sm font-medium transition">
    {{ $slot }}
</button>
