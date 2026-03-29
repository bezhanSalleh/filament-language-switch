@props([
    'src',
    'alt' => '',
])

<img
    src="{{ $src }}"
    alt="{{ $alt }}"
    loading="lazy"
    {{ $attributes->class(['fi-avatar fi-ls-flag']) }}
/>
