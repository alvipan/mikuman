@props(["name"])

<template x-if="tab === '{{ $name }}'">
    <div>
        {{ $slot }}
    </div>
</template>
