@props([
    'locale',
    'active' => false,
])

<span {{ $attributes->class([
    'fi-ls-avatar flex items-center justify-center shrink-0 rounded-md text-xs font-semibold',
    'bg-primary-50 text-primary-600 dark:bg-primary-400/10 dark:text-primary-400' => $active,
    'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400' => ! $active,
]) }}>
    {{ str($locale)->length() > 2 ? str($locale)->substr(0, 2)->upper() : str($locale)->upper() }}
</span>
