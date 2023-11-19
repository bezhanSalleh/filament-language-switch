@props([
    'src',
    'alt' => '',
    'circular' => false,
])
<img
    src="{{ $src }}"
    {{ $attributes
        ->class([
            'object-cover object-center max-w-none',
            'rounded-full' => $circular,
            'rounded-md' => ! $circular,
        ])
    }}
    @class([

    ])
/>