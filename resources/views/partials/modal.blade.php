@include('language-switch::partials.trigger', [
    'attributes' => new \Illuminate\View\ComponentAttributeBag([
        'x-on:click' => "\$dispatch('open-modal', { id: 'fls-modal' })",
    ]),
])

<x-filament::modal
    id="fls-modal"
    teleport="body"
    :heading="$ls->getModalHeading()"
    :width="$ls->getModalWidth() ?? 'md'"
    :slide-over="$ls->isModalSlideOver()"
    :sticky-header="$ls->isModalSlideOver()"
    :icon="$ls->getModalIcon()"
    :icon-color="$ls->getModalIconColor()"
>
    @include($contentView ?? 'language-switch::partials.list')
</x-filament::modal>
