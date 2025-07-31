@props([
    'src',
    'alt' => '',
    'circular' => false,
    'switch' => false,
])
<img
    src="{{ $src }}"
    {{ $attributes
        ->class([
            'object-cover object-center',
            'rounded-full w-7 h-7' => $circular,
            'rounded-lg' => ! $circular && ! $switch,
            'rounded-md' => ! $circular && $switch,
        ])
        ->merge(['alt' => $alt])
    }}
/>
