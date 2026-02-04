<div>
    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Predlošci</h1>
        <button
            wire:click="openCreateModal"
            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
        >
            Novi predložak
        </button>
    </div>

    {{-- Template list --}}
    <div class="space-y-3">
        @forelse($this->templates as $template)
            <div
                class="p-4 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg border-l-4"
                style="border-left-color: var(--color-{{ $template->color }}-{{ $template->color === 'gray' ? '400' : '500' }})"
                wire:key="template-{{ $template->id }}"
            >
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <h3 class="font-medium text-gray-900 dark:text-white mb-1">{{ $template->name }}</h3>
                        @if($template->description)
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">{{ $template->description }}</p>
                        @endif
                        <span class="text-sm text-gray-500 dark:text-gray-400">{{ $template->items_count }} artikala</span>
                    </div>
                    <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                        <button @click="open = !open" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                            </svg>
                        </button>
                        <div
                            x-show="open"
                            x-transition
                            class="absolute right-0 top-full mt-1 w-40 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg z-10 py-1"
                        >
                            <button
                                @click="open = false; $wire.viewTemplate({{ $template->id }})"
                                class="w-full px-4 py-2 text-left text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
                            >
                                Pregledaj
                            </button>
                            <button
                                @click="open = false; $wire.openEditModal({{ $template->id }})"
                                class="w-full px-4 py-2 text-left text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
                            >
                                Uredi
                            </button>
                            <button
                                wire:click="deleteTemplate({{ $template->id }})"
                                wire:confirm="Obrisati ovaj predložak?"
                                @click="open = false"
                                class="w-full px-4 py-2 text-left text-sm text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700"
                            >
                                Obriši
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-12">
                <svg class="w-16 h-16 mx-auto text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Nema predložaka</h3>
                <p class="text-gray-500 dark:text-gray-400">Stvorite prvi predložak</p>
            </div>
        @endforelse
    </div>

    {{-- Create/Edit modal --}}
    @if($showModal)
        <div
            class="fixed inset-0 z-50"
            x-data="templateForm(@js($this->allItems), @js($formItemIds), '{{ $formColor }}')"
            @keydown.escape.window="$wire.set('showModal', false)"
        >
            <div class="absolute inset-0 bg-black/50" wire:click="$set('showModal', false)"></div>
            <div class="absolute inset-0 flex items-end sm:items-center justify-center sm:p-4">
                <div class="bg-white dark:bg-gray-800 rounded-t-2xl sm:rounded-2xl shadow-xl w-full max-w-lg max-h-[90vh] flex flex-col">
                    <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                            {{ $editingId ? 'Uredi predložak' : 'Novi predložak' }}
                        </h2>
                        <button type="button" wire:click="$set('showModal', false)" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="flex-1 overflow-y-auto p-4 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Naziv</label>
                            <input
                                type="text"
                                wire:model="formName"
                                required
                                autofocus
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Opis (opcionalno)</label>
                            <textarea
                                wire:model="formDescription"
                                rows="2"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            ></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Boja</label>
                            <div class="flex flex-wrap gap-2">
                                @foreach(['gray','red','orange','amber','yellow','lime','green','emerald','teal','cyan','sky','blue','indigo','violet','purple','fuchsia','pink','rose'] as $c)
                                    <button
                                        type="button"
                                        @click="color = '{{ $c }}'"
                                        class="w-8 h-8 rounded-full ring-2 ring-offset-2 ring-offset-white dark:ring-offset-gray-800 transition-all"
                                        :class="color === '{{ $c }}' ? 'ring-current' : 'ring-transparent'"
                                        style="background-color: var(--color-{{ $c }}-{{ $c === 'gray' ? '400' : '500' }})"
                                    ></button>
                                @endforeach
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Artikli <span class="text-gray-400 font-normal" x-text="'(' + selected.length + ' odabrano)'"></span>
                            </label>
                            <input
                                type="text"
                                x-model="search"
                                placeholder="Pretraži artikle..."
                                class="w-full px-4 py-2 mb-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            />
                            <div class="flex items-center gap-2 mb-2 overflow-x-auto pb-1">
                                <button
                                    type="button"
                                    @click="catFilter = ''"
                                    class="inline-flex items-center justify-center h-7 px-2.5 text-xs rounded-full whitespace-nowrap transition-colors flex-shrink-0"
                                    :class="catFilter === '' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300' : 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'"
                                >
                                    Sve
                                </button>
                                @foreach($this->categories as $cat)
                                    <button
                                        type="button"
                                        @click="catFilter = '{{ $cat }}'"
                                        class="inline-flex items-center justify-center h-7 px-2.5 text-xs rounded-full whitespace-nowrap capitalize transition-colors flex-shrink-0"
                                        :class="catFilter === '{{ $cat }}' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300' : 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'"
                                    >
                                        {{ $cat }}
                                    </button>
                                @endforeach
                            </div>
                            <div class="space-y-1 max-h-48 overflow-y-auto">
                                <template x-for="item in filteredItems" :key="item.id">
                                    <label class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 cursor-pointer">
                                        <input
                                            type="checkbox"
                                            :checked="selected.includes(item.id)"
                                            @change="toggleItem(item.id)"
                                            class="w-4 h-4 rounded border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-blue-500"
                                        />
                                        <span class="flex-1 text-sm text-gray-900 dark:text-white" x-text="item.name"></span>
                                        <span class="text-xs px-2 py-0.5 rounded bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 capitalize" x-text="item.category"></span>
                                    </label>
                                </template>
                                <div x-show="filteredItems.length === 0" class="text-center py-4 text-sm text-gray-500 dark:text-gray-400">
                                    Nema artikala
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 p-4 border-t border-gray-200 dark:border-gray-700">
                        <button
                            type="button"
                            wire:click="$set('showModal', false)"
                            class="px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors"
                        >
                            Odustani
                        </button>
                        <button
                            type="button"
                            @click="$wire.save(color, selected)"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
                        >
                            Spremi
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- View modal --}}
    @if($showViewModal)
        <div class="fixed inset-0 z-50" @keydown.escape.window="$wire.set('showViewModal', false)">
            <div class="absolute inset-0 bg-black/50" wire:click="$set('showViewModal', false)"></div>
            <div class="absolute inset-0 flex items-center justify-center p-4">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-lg w-full max-h-[80vh] flex flex-col">
                    <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $viewName }}</h2>
                        <button wire:click="$set('showViewModal', false)" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="flex-1 overflow-y-auto p-4">
                        @if(empty($viewItems))
                            <div class="text-center py-8 text-gray-500">Predložak nema artikala</div>
                        @else
                            @foreach($viewItems as $item)
                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg mb-2">
                                    <span class="font-medium text-gray-900 dark:text-white">{{ $item['name'] }}</span>
                                    <span class="text-xs px-2 py-0.5 rounded bg-gray-200 dark:bg-gray-600 text-gray-600 dark:text-gray-300 capitalize">{{ $item['category'] }}</span>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@script
<script>
    Alpine.data('templateForm', (allItems, initialSelected, initialColor) => ({
        search: '',
        catFilter: '',
        color: initialColor,
        selected: [...initialSelected],

        get filteredItems() {
            let items = allItems;
            if (this.catFilter) {
                items = items.filter(i => i.category === this.catFilter);
            }
            if (this.search) {
                const q = this.search.toLowerCase();
                items = items.filter(i => i.name.toLowerCase().includes(q));
            }
            // Selected items first
            items.sort((a, b) => {
                const aS = this.selected.includes(a.id) ? 0 : 1;
                const bS = this.selected.includes(b.id) ? 0 : 1;
                if (aS !== bS) return aS - bS;
                return a.name.localeCompare(b.name, 'hr');
            });
            return items;
        },

        toggleItem(id) {
            const idx = this.selected.indexOf(id);
            if (idx === -1) {
                this.selected.push(id);
            } else {
                this.selected.splice(idx, 1);
            }
        }
    }));
</script>
@endscript
