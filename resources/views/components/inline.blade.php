@props([
    'key' => null,
])

@php
    $key ??= uniqid('fls-inline-', true);
@endphp

<livewire:language-switch-component :key="$key" />
