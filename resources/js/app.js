import Sortable from 'sortablejs';

document.addEventListener('alpine:init', () => {
    Alpine.directive('sortable', (el) => {
        const method = el.dataset.sortableMethod;
        const wireEl = el.closest('[wire\\:id]');
        const component = Livewire.find(wireEl.getAttribute('wire:id'));

        Sortable.create(el, {
            handle: '[data-sortable-handle]',
            animation: 150,
            ghostClass: 'opacity-50',
            onEnd() {
                const ids = [...el.querySelectorAll('[data-sortable-item]')].map(
                    item => parseInt(item.dataset.sortableItem)
                );
                component.call(method, ids);
            }
        });
    });
});
