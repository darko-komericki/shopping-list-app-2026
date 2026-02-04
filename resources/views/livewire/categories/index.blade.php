<div>
    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Kategorije</h1>
        <button
            wire:click="openCreateModal"
            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
        >
            Nova kategorija
        </button>
    </div>

    {{-- Category list --}}
    <div class="space-y-2" x-data x-sortable data-sortable-method="reorder">
        @forelse($this->categories as $index => $category)
            <div
                class="flex items-center gap-2 sm:gap-3 px-3 py-3 sm:p-4 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700"
                wire:key="cat-{{ $category->id }}"
                data-sortable-item="{{ $category->id }}"
            >
                {{-- Drag handle --}}
                <div data-sortable-handle class="cursor-grab active:cursor-grabbing touch-none p-1 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" />
                    </svg>
                </div>

                <span
                    class="w-4 h-4 rounded-full flex-shrink-0"
                    style="background-color: var(--color-{{ $category->color }}-{{ $category->color === 'gray' ? '400' : '500' }})"
                ></span>

                <div class="flex-1 min-w-0 truncate">
                    <span class="font-medium text-gray-900 dark:text-white capitalize">{{ $category->name }}</span>
                    <span class="text-sm text-gray-500 dark:text-gray-400 whitespace-nowrap"> ({{ $category->item_count }})</span>
                </div>

                <div class="flex items-center gap-1 sm:gap-2 flex-shrink-0">
                    <button
                        wire:click="openEditModal({{ $category->id }})"
                        class="p-1.5 sm:p-2 text-gray-400 hover:text-blue-500 transition-colors"
                    >
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                    </button>
                    <button
                        wire:click="deleteCategory({{ $category->id }})"
                        wire:confirm="Obrisati ovu kategoriju?"
                        class="p-1.5 sm:p-2 text-gray-400 hover:text-red-500 transition-colors"
                    >
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>
            </div>
        @empty
            <div class="text-center py-12">
                <svg class="w-16 h-16 mx-auto text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z" />
                </svg>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Nema kategorija</h3>
                <p class="text-gray-500 dark:text-gray-400">Dodajte prvu kategoriju</p>
            </div>
        @endforelse
    </div>

    {{-- Create/Edit modal --}}
    @if($showModal)
        <div
            class="fixed inset-0 z-50"
            x-data="{ color: '{{ $formColor }}' }"
            @keydown.escape.window="$wire.set('showModal', false)"
        >
            <div class="absolute inset-0 bg-black/50" wire:click="$set('showModal', false)"></div>
            <div class="absolute inset-0 flex items-center justify-center p-4">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        {{ $editingId ? 'Uredi kategoriju' : 'Nova kategorija' }}
                    </h2>
                    <div class="space-y-4">
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
                        <div class="flex justify-end gap-3 pt-4">
                            <button
                                type="button"
                                wire:click="$set('showModal', false)"
                                class="px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors"
                            >
                                Odustani
                            </button>
                            <button
                                type="button"
                                @click="$wire.save(color)"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
                            >
                                Spremi
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Toast notification --}}
    <div
        x-data="{ message: '', show: false }"
        @notify.window="message = $event.detail.message; show = true; setTimeout(() => show = false, 3000)"
        x-show="show"
        x-transition
        class="fixed bottom-20 sm:bottom-4 left-1/2 -translate-x-1/2 z-50 px-4 py-2 bg-red-600 text-white rounded-lg shadow-lg text-sm"
        style="display: none;"
        x-text="message"
    ></div>
</div>
