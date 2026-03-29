@props([
    'locale',
])

<span {{ $attributes->class([
    'fi-ls-avatar flex items-center justify-center shrink-0 rounded-md',
    'bg-primary-500/10 text-xs font-semibold text-primary-600 dark:text-primary-400',
]) }}>
    {{ str($locale)->length() > 2 ? str($locale)->substr(0, 2)->upper() : str($locale)->upper() }}
</span>
