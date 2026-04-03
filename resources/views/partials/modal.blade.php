<x-language-switch::trigger x-on:click="$dispatch('open-modal', { id: 'fls-modal' })" />

<x-filament::modal
    id="fls-modal"
    teleport="body"
    :heading="$ls->getModalHeading()"
    :width="$ls->getModalWidth() ?? 'sm'"
    :slide-over="$ls->isModalSlideOver()"
    :sticky-header="$ls->isModalSlideOver()"
    :icon="$ls->getModalIcon()"
    :icon-color="$ls->getModalIconColor()"
    :class="$isFlagsOnly ? '[&_.fi-modal-window]:w-fit' : ''"
>
    @include($contentView ?? 'language-switch::partials.list')
</x-filament::modal>
