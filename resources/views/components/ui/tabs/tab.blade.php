@props(["name"])

<button type="button" @click="tab = '{{ $name }}'"
    :class="tab === '{{ $name }}'
        ?
        'border-b-2 border-white text-white' :
        'text-gray-500'"
    class="p-3 text-sm font-medium transition">
    {{ $slot }}
</button>
