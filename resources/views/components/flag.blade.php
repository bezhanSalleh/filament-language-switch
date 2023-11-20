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
            'object-cover object-center max-w-none',
            'rounded-full' => $circular,
            'rounded-lg' => ! $circular && ! $switch,
            'rounded-md' => ! $circular && $switch,
            ''
        ])
    }}
    @class([

    ])
/>