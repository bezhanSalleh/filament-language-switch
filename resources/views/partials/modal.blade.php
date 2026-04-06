<x-language-switch::trigger
    :layout="$layout"
    x-on:click="$dispatch('open-modal', { id: 'fls-modal' })"
/>

<x-filament::modal
    id="fls-modal"
    teleport="body"
    :heading="$ls->getModalHeading()"
    :width="$ls->getModalWidth() ?? 'sm'"
    :slide-over="$ls->isModalSlideOver()"
    :sticky-header="$ls->isModalSlideOver()"
    :icon="$ls->getModalIcon()"
    :icon-color="$ls->getModalIconColor()"
    :class="$itemStyle->isCompact() ? '[&_.fi-modal-window]:w-fit' : ''"
>
    @include('language-switch::partials.list')
</x-filament::modal>
