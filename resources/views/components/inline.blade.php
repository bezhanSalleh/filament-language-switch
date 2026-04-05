@props([
    'key' => null,
])

@php
    $key ??= 'fls-inline-' . md5((string) microtime(true));
@endphp

<livewire:language-switch-component :key="$key" />
