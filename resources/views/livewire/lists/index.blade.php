<div class="space-y-8">
    {{-- Create new list --}}
    <section>
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Create new list</h2>
        <form wire:submit="createList" class="flex flex-col sm:flex-row gap-3">
            <input
                type="text"
                wire:model="name"
                placeholder="List name (optional)"
                class="flex-1 px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            />
            <button
                type="submit"
                class="w-full sm:w-auto px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors"
            >
                Create
            </button>
        </form>
    </section>

    {{-- Quick start from template --}}
    @if($templates->isNotEmpty())
        <section>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Quick start from template</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                @foreach($templates as $template)
                    <button
                        wire:click="createFromTemplate({{ $template->id }})"
                        class="p-4 text-left border border-gray-200 dark:border-gray-700 rounded-lg border-l-4 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors"
                        style="border-left-color: var(--color-{{ $template->color }}-{{ $template->color === 'gray' ? '400' : '500' }})"
                    >
                        <div class="flex items-center justify-between mb-1">
                            <span class="font-medium text-gray-900 dark:text-white">{{ $template->name }}</span>
                            <span class="text-sm text-gray-500 dark:text-gray-400">{{ $template->items_count }} items</span>
                        </div>
                        @if($template->description)
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $template->description }}</p>
                        @endif
                    </button>
                @endforeach
            </div>
        </section>
    @endif

    {{-- Recent lists --}}
    @if($lists->isNotEmpty())
        <section>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Recent lists</h2>
            <div class="space-y-2">
                @foreach($lists as $list)
                    <div class="group flex items-center border border-gray-200 dark:border-gray-700 rounded-lg hover:border-blue-500 dark:hover:border-blue-400 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                        <a
                            href="{{ route('lists.show', $list) }}"
                            class="flex-1 p-4"
                        >
                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="font-medium text-gray-900 dark:text-white">{{ $list->name }}</span>
                                    <span class="ml-2 text-sm text-gray-400">{{ $list->list_items_count }} items</span>
                                </div>
                                <span class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $list->updated_at->format('d.m.Y.') }}
                                </span>
                            </div>
                        </a>
                        <button
                            wire:click="deleteList('{{ $list->id }}')"
                            wire:confirm="Delete this list?"
                            class="p-4 text-gray-400 hover:text-red-500 opacity-0 group-hover:opacity-100 transition-opacity"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    {{-- Empty state --}}
    @if($lists->isEmpty())
        <div class="text-center py-12">
            <svg class="w-16 h-16 mx-auto text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No lists yet</h3>
            <p class="text-gray-500 dark:text-gray-400">Create your first shopping list</p>
        </div>
    @endif
</div>
