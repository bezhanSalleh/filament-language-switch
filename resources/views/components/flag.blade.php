@props([
    'src',
    'alt' => '',
])

<img
    src="{{ $src }}"
    alt="{{ $alt }}"
    loading="lazy"
    {{ $attributes->class(['fi-ls-flag shrink-0 object-cover object-center']) }}
/>
