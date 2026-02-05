<div>
    {{-- Header: name + menu --}}
    <div class="mb-6 flex items-center justify-between gap-4">
        <div class="flex-1 min-w-0">
            @if($editingName)
                <input
                    type="text"
                    wire:model="listName"
                    wire:keydown.enter="saveName"
                    wire:blur="saveName"
                    class="w-full text-2xl font-bold bg-transparent border-b-2 border-blue-500 focus:outline-none text-gray-900 dark:text-white"
                    autofocus
                />
            @else
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white truncate">
                    {{ $listName }}
                </h1>
            @endif
        </div>

        {{-- Three-dot menu --}}
        <div class="relative flex-shrink-0" x-data="{ open: false }" @click.outside="open = false">
            <button @click="open = !open" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                </svg>
            </button>
            <div
                x-show="open"
                x-transition
                class="absolute right-0 top-full mt-1 w-48 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg z-10 py-1"
            >
                <button
                    @click="open = false; $wire.set('editingName', true)"
                    class="w-full px-4 py-2 text-left text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center gap-2"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                    Preimenuj
                </button>
                <button
                    wire:click="deleteList"
                    wire:confirm="Lista i svi njeni artikli bit će trajno obrisani."
                    @click="open = false"
                    class="w-full px-4 py-2 text-left text-sm text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center gap-2"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Obriši listu
                </button>
            </div>
        </div>
    </div>

    {{-- Empty state --}}
    @if($this->uncheckedItems->isEmpty() && $this->checkedItems->isEmpty())
        <div class="text-center py-12">
            <svg class="w-16 h-16 mx-auto text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Lista je prazna</h3>
            <p class="text-gray-500 dark:text-gray-400 mb-6">Dodajte artikle za početak</p>
            <button
                wire:click="$set('showItemPicker', true)"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
            >
                Dodaj artikle
            </button>
        </div>
    @else
        {{-- Unchecked items --}}
        @if($this->uncheckedItems->isNotEmpty())
            <div class="space-y-2 mb-6">
                @foreach($this->uncheckedItems as $listItem)
                    <div
                        class="relative overflow-hidden rounded-lg sm:overflow-visible"
                        x-data="swipeItem()"
                        wire:key="item-{{ $listItem->id }}"
                    >
                        {{-- Swipe backgrounds (mobile) --}}
                        <div class="absolute inset-0 bg-red-500 flex items-center justify-start pl-4 sm:hidden rounded-lg" x-show="offset > 0" x-cloak>
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </div>
                        <div class="absolute inset-0 bg-green-500 flex items-center justify-end pr-4 sm:hidden rounded-lg" x-show="offset < 0" x-cloak>
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>

                        {{-- Card --}}
                        <div
                            class="relative p-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700"
                            :style="cardStyle()"
                            @touchstart="onTouchStart($event)"
                            @touchmove="onTouchMove($event)"
                            @touchend="onTouchEnd($event, {{ $listItem->id }}, '{{ addslashes($listItem->item->name) }}')"
                        >
                            <div class="flex items-center gap-3">
                                {{-- Desktop checkbox --}}
                                <button
                                    type="button"
                                    wire:click="toggleItem({{ $listItem->id }})"
                                    class="hidden sm:flex w-6 h-6 flex-shrink-0 items-center justify-center rounded border-2 border-gray-300 dark:border-gray-600 hover:border-green-500 transition-colors"
                                ></button>

                                <div class="flex-1 min-w-0">
                                    <span class="font-medium text-gray-900 dark:text-white">{{ $listItem->item->name }}</span>
                                    @include('livewire.lists._category-badge', ['category' => $listItem->item->category])
                                </div>
                            </div>

                            <div class="flex items-center justify-between mt-2 sm:pl-9">
                                {{-- Quantity stepper --}}
                                <div class="flex items-center gap-1">
                                    <button
                                        type="button"
                                        wire:click="updateQuantity({{ $listItem->id }}, -1)"
                                        @if($listItem->quantity <= 1) disabled @endif
                                        class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                        </svg>
                                    </button>
                                    <span class="w-16 text-center text-sm font-medium text-gray-900 dark:text-white">
                                        {{ rtrim(rtrim(number_format($listItem->quantity, 2), '0'), '.') }} {{ $listItem->unit }}
                                    </span>
                                    <button
                                        type="button"
                                        wire:click="updateQuantity({{ $listItem->id }}, 1)"
                                        class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                    </button>
                                </div>

                                {{-- Desktop delete button --}}
                                <button
                                    type="button"
                                    wire:click="removeItem({{ $listItem->id }})"
                                    class="hidden sm:flex w-8 h-8 items-center justify-center text-gray-400 hover:text-red-500 transition-colors"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Checked items --}}
        @if($this->checkedItems->isNotEmpty())
            <div class="mb-6">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                        Označeno ({{ $this->checkedItems->count() }})
                    </h3>
                    <button
                        wire:click="clearChecked"
                        wire:confirm="Svi označeni artikli bit će uklonjeni s liste."
                        class="text-sm text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300"
                    >
                        Obriši sve
                    </button>
                </div>
                <div class="space-y-2">
                    @foreach($this->checkedItems as $listItem)
                        <div
                            class="relative overflow-hidden rounded-lg sm:overflow-visible"
                            x-data="swipeItem()"
                            wire:key="item-{{ $listItem->id }}"
                        >
                            {{-- Swipe backgrounds (mobile) --}}
                            <div class="absolute inset-0 bg-red-500 flex items-center justify-start pl-4 sm:hidden rounded-lg" x-show="offset > 0" x-cloak>
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </div>
                            <div class="absolute inset-0 bg-amber-500 flex items-center justify-end pr-4 sm:hidden rounded-lg" x-show="offset < 0" x-cloak>
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" />
                                </svg>
                            </div>

                            {{-- Card --}}
                            <div
                                class="relative p-3 bg-white dark:bg-gray-800 rounded-lg border border-green-300 dark:border-green-800 sm:opacity-60 sm:border-gray-200 sm:dark:border-gray-700"
                                :style="cardStyle()"
                                @touchstart="onTouchStart($event)"
                                @touchmove="onTouchMove($event)"
                                @touchend="onTouchEnd($event, {{ $listItem->id }}, '{{ addslashes($listItem->item->name) }}')"
                            >
                                <div class="flex items-center gap-3">
                                    {{-- Checked indicator (mobile) --}}
                                    <svg class="w-5 h-5 text-green-500 flex-shrink-0 sm:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>

                                    {{-- Desktop checkbox --}}
                                    <button
                                        type="button"
                                        wire:click="toggleItem({{ $listItem->id }})"
                                        class="hidden sm:flex w-6 h-6 flex-shrink-0 items-center justify-center rounded border-2 bg-green-500 border-green-500 text-white transition-colors"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </button>

                                    <div class="flex-1 min-w-0">
                                        <span class="font-medium line-through text-gray-400 dark:text-gray-500">{{ $listItem->item->name }}</span>
                                        @include('livewire.lists._category-badge', ['category' => $listItem->item->category])
                                    </div>
                                </div>

                                <div class="flex items-center justify-between mt-2 sm:pl-9">
                                    <div class="flex items-center gap-1">
                                        <button
                                            type="button"
                                            wire:click="updateQuantity({{ $listItem->id }}, -1)"
                                            @if($listItem->quantity <= 1) disabled @endif
                                            class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                            </svg>
                                        </button>
                                        <span class="w-16 text-center text-sm font-medium text-gray-900 dark:text-white">
                                            {{ rtrim(rtrim(number_format($listItem->quantity, 2), '0'), '.') }} {{ $listItem->unit }}
                                        </span>
                                        <button
                                            type="button"
                                            wire:click="updateQuantity({{ $listItem->id }}, 1)"
                                            class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                            </svg>
                                        </button>
                                    </div>

                                    <button
                                        type="button"
                                        wire:click="removeItem({{ $listItem->id }})"
                                        class="hidden sm:flex w-8 h-8 items-center justify-center text-gray-400 hover:text-red-500 transition-colors"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- FAB buttons --}}
        <div class="fixed bottom-28 sm:bottom-6 right-6 flex gap-2 z-10">
            <button
                wire:click="$set('showTemplatePicker', true)"
                class="w-14 h-14 bg-gray-600 text-white rounded-full shadow-lg hover:bg-gray-700 transition-colors flex items-center justify-center"
                title="Dodaj iz predloška"
            >
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
            </button>
            <button
                wire:click="$set('showItemPicker', true)"
                class="w-14 h-14 bg-blue-600 text-white rounded-full shadow-lg hover:bg-blue-700 transition-colors flex items-center justify-center"
                title="Dodaj artikle"
            >
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
            </button>
        </div>
    @endif

    {{-- Item picker modal --}}
    @if($showItemPicker)
        <div class="fixed inset-0 z-50 flex items-end sm:items-center justify-center" x-data="{ showCreateForm: false, newName: @entangle('search'), newCategory: 'ostalo', newUnit: 'kom' }" @keydown.escape.window="$wire.set('showItemPicker', false)">
            <div class="absolute inset-0 bg-black/50" wire:click="$set('showItemPicker', false)"></div>

            <div class="relative bg-white dark:bg-gray-800 rounded-t-2xl sm:rounded-2xl w-full max-w-lg max-h-[80vh] flex flex-col">
                {{-- Header --}}
                <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Dodaj artikle</h2>
                    <button
                        type="button"
                        wire:click="$set('showItemPicker', false)"
                        class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Search --}}
                <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                    <input
                        type="search"
                        wire:model.live.debounce.300ms="search"
                        placeholder="Pretraži artikle..."
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    />
                </div>

                {{-- Category filter --}}
                <div class="border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center gap-2 px-3 py-3 overflow-x-auto sm:flex-wrap sm:overflow-visible">
                        <button
                            wire:click="$set('activeCategory', null)"
                            class="inline-flex items-center justify-center h-8 px-3 text-sm rounded-full whitespace-nowrap transition-colors flex-shrink-0 {{ !$activeCategory ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300' : 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}"
                        >
                            Sve
                        </button>
                        @foreach($this->categories as $cat)
                            <button
                                wire:click="$set('activeCategory', '{{ $cat }}')"
                                class="inline-flex items-center justify-center h-8 px-3 text-sm rounded-full whitespace-nowrap capitalize transition-colors flex-shrink-0 {{ $activeCategory === $cat ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300' : 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}"
                            >
                                {{ $cat }}
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- Items list --}}
                <div class="flex-1 overflow-y-auto p-4 pb-20 sm:pb-4">
                    <template x-if="showCreateForm">
                        <div class="space-y-4">
                            <div class="flex items-center gap-2 mb-2">
                                <button @click="showCreateForm = false" class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                    </svg>
                                </button>
                                <h3 class="text-base font-semibold text-gray-900 dark:text-white">Novi artikl</h3>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Naziv</label>
                                <input
                                    x-model="newName"
                                    type="text"
                                    placeholder="Naziv artikla..."
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    x-ref="createName"
                                    x-init="$nextTick(() => $refs.createName?.focus())"
                                />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kategorija</label>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($this->categories as $cat)
                                        <button
                                            type="button"
                                            @click="newCategory = '{{ $cat }}'"
                                            class="inline-flex items-center justify-center h-8 px-3 text-sm rounded-full whitespace-nowrap capitalize transition-colors"
                                            :class="newCategory === '{{ $cat }}' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300' : 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'"
                                        >
                                            {{ $cat }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jedinica</label>
                                <div class="flex flex-wrap gap-2">
                                    @foreach(['kom', 'kg', 'g', 'l', 'ml'] as $u)
                                        <button
                                            type="button"
                                            @click="newUnit = '{{ $u }}'"
                                            class="inline-flex items-center justify-center h-8 px-3 text-sm rounded-full whitespace-nowrap transition-colors"
                                            :class="newUnit === '{{ $u }}' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300' : 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'"
                                        >
                                            {{ $u }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>

                            <button
                                @click="$wire.createAndAddItem(newName, newCategory, newUnit); showCreateForm = false"
                                :disabled="!newName.trim()"
                                class="w-full py-2.5 px-4 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                            >
                                Stvori i dodaj na popis
                            </button>
                        </div>
                    </template>

                    <template x-if="!showCreateForm">
                        <div>
                            {{-- Create new item button --}}
                            <button
                                @click="showCreateForm = true"
                                class="w-full flex items-center gap-3 p-3 mb-2 rounded-lg border border-dashed border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors text-left"
                            >
                                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                <span class="flex-1 font-medium text-blue-600 dark:text-blue-400">Novi artikl</span>
                            </button>

                            @if($this->availableItems->isEmpty())
                                <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                                    <p class="mb-3">Nema pronađenih artikala</p>
                                    @if($search)
                                        <button
                                            @click="showCreateForm = true"
                                            class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                            </svg>
                                            Stvori "{{ $search }}"
                                        </button>
                                    @endif
                                </div>
                            @else
                                <div class="space-y-2">
                                    @foreach($this->availableItems as $item)
                                        <button
                                            wire:click="addItem({{ $item->id }})"
                                            wire:key="available-{{ $item->id }}"
                                            class="w-full flex items-center gap-3 p-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors text-left"
                                        >
                                            <span class="flex-1 font-medium text-gray-900 dark:text-white">{{ $item->name }}</span>
                                            @include('livewire.lists._category-badge', ['category' => $item->category])
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                            </svg>
                                        </button>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </template>
                </div>
            </div>
        </div>
    @endif

    {{-- Template picker modal --}}
    @if($showTemplatePicker)
        <div class="fixed inset-0 z-50 flex items-end sm:items-center justify-center" @keydown.escape.window="$wire.set('showTemplatePicker', false)">
            <div class="absolute inset-0 bg-black/50" wire:click="$set('showTemplatePicker', false)"></div>

            <div class="relative bg-white dark:bg-gray-800 rounded-t-2xl sm:rounded-2xl w-full max-w-lg max-h-[80vh] flex flex-col">
                <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Dodaj iz predloška</h2>
                    <button
                        type="button"
                        wire:click="$set('showTemplatePicker', false)"
                        class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto p-4 pb-20 sm:pb-4">
                    @if($this->templates->isEmpty())
                        <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                            <p>Nemate predložaka</p>
                        </div>
                    @else
                        <div class="space-y-2">
                            @foreach($this->templates as $template)
                                <button
                                    wire:click="addFromTemplate({{ $template->id }})"
                                    class="w-full flex items-center justify-between gap-3 p-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors text-left"
                                >
                                    <div class="min-w-0">
                                        <span class="font-medium text-gray-900 dark:text-white">{{ $template->name }}</span>
                                        @if($template->description)
                                            <p class="text-sm text-gray-500 dark:text-gray-400 truncate">{{ $template->description }}</p>
                                        @endif
                                    </div>
                                    <span class="flex-shrink-0 text-sm text-gray-400">{{ $template->items_count }} {{ $template->items_count === 1 ? 'artikl' : 'artikala' }}</span>
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    {{-- Undo delete toast --}}
    <div
        x-data
        x-show="$store.undo.active"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="translate-y-full opacity-0"
        x-transition:enter-end="translate-y-0 opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="translate-y-0 opacity-100"
        x-transition:leave-end="translate-y-full opacity-0"
        x-cloak
        class="fixed bottom-28 sm:bottom-6 left-4 right-4 sm:left-auto sm:right-6 sm:w-80 z-20"
    >
        <div class="flex items-center justify-between gap-3 px-4 py-3 bg-gray-900 dark:bg-gray-700 text-white rounded-lg shadow-lg">
            <span class="text-sm truncate" x-text="$store.undo.itemName + ' obrisan/a'"></span>
            <button
                @click="$store.undo.restore()"
                class="flex-shrink-0 text-sm font-semibold text-blue-400 hover:text-blue-300 uppercase tracking-wide"
            >
                Poništi
            </button>
        </div>
    </div>
</div>

@script
<script>
    Alpine.store('undo', {
        active: false,
        itemName: '',
        _listItemId: null,
        _timer: null,
        _wrapper: null,
        _wire: null,

        schedule(listItemId, itemName, wrapper, wire) {
            // If there's already a pending delete, execute it immediately
            if (this._timer) {
                clearTimeout(this._timer);
                this._wire?.removeItem(this._listItemId);
                this._cleanup();
            }

            this.active = true;
            this.itemName = itemName;
            this._listItemId = listItemId;
            this._wrapper = wrapper;
            this._wire = wire;

            this._timer = setTimeout(() => {
                this._wire?.removeItem(this._listItemId);
                this.active = false;
                this._cleanup();
            }, 4000);
        },

        restore() {
            clearTimeout(this._timer);

            if (this._wrapper) {
                // Reset Alpine swipeItem state so touch events work again
                const data = Alpine.$data(this._wrapper);
                if (data) {
                    data.offset = 0;
                    data.animating = false;
                    data.swiping = false;
                }

                // Expand the row back
                this._wrapper.style.transition = 'height 250ms ease, opacity 250ms ease, margin 250ms ease';
                this._wrapper.style.height = '';
                this._wrapper.style.opacity = '';
                this._wrapper.style.marginTop = '';
                this._wrapper.style.marginBottom = '';
                this._wrapper.style.overflow = '';
            }

            this.active = false;
            this._cleanup();
        },

        _cleanup() {
            this._listItemId = null;
            this._timer = null;
            this._wrapper = null;
            this._wire = null;
        }
    });

    Alpine.data('swipeItem', () => ({
        offset: 0,
        startX: 0,
        startY: 0,
        swiping: false,
        animating: false,
        direction: null,

        cardStyle() {
            return {
                transform: `translateX(${this.offset}px)`,
                transition: this.swiping ? 'none' : 'transform 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94)'
            };
        },

        rubberBand(x, limit) {
            if (Math.abs(x) < limit) return x;
            const sign = x < 0 ? -1 : 1;
            const overshoot = Math.abs(x) - limit;
            return sign * (limit + Math.log10(1 + overshoot / 10) * 15);
        },

        collapseRow() {
            const wrapper = this.$el.closest('[wire\\:key]');
            if (!wrapper) return Promise.resolve();

            const height = wrapper.offsetHeight;
            wrapper.style.height = height + 'px';
            wrapper.style.overflow = 'hidden';
            wrapper.style.transition = 'height 250ms ease, opacity 250ms ease, margin 250ms ease';
            wrapper.offsetHeight; // force reflow

            return new Promise(resolve => {
                wrapper.style.height = '0px';
                wrapper.style.opacity = '0';
                wrapper.style.marginTop = '0px';
                wrapper.style.marginBottom = '0px';
                setTimeout(resolve, 260);
            });
        },

        onTouchStart(e) {
            if (this.animating) return;
            this.startX = e.touches[0].clientX;
            this.startY = e.touches[0].clientY;
            this.direction = null;
            this.swiping = true;
        },

        onTouchMove(e) {
            if (!this.swiping || this.animating) return;
            const diffX = e.touches[0].clientX - this.startX;
            const diffY = e.touches[0].clientY - this.startY;

            if (!this.direction && (Math.abs(diffX) > 10 || Math.abs(diffY) > 10)) {
                this.direction = Math.abs(diffX) > Math.abs(diffY) ? 'h' : 'v';
            }

            if (this.direction !== 'h') return;
            this.offset = this.rubberBand(diffX, 80);
        },

        async onTouchEnd(e, listItemId, itemName) {
            if (!this.swiping || this.animating) return;
            this.swiping = false;
            this.direction = null;

            const screenW = window.innerWidth;

            if (this.offset > 80) {
                this.animating = true;
                this.offset = screenW;
                await new Promise(r => setTimeout(r, 280));

                const wrapper = this.$el.closest('[wire\\:key]');
                await this.collapseRow();

                Alpine.store('undo').schedule(listItemId, itemName, wrapper, this.$wire);
            } else if (this.offset < -80) {
                this.animating = true;
                this.offset = -screenW;
                await new Promise(r => setTimeout(r, 280));
                await this.collapseRow();
                this.$wire.toggleItem(listItemId);
            } else {
                this.offset = 0;
            }
        }
    }));
</script>
@endscript
