<?php

namespace App\Livewire\Lists;

use App\Models\ListItem;
use App\Models\ShoppingList;
use App\Models\Template;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Index extends Component
{
    public string $name = '';

    public function createList(): void
    {
        $list = Auth::user()->shoppingLists()->create([
            'name' => $this->name ?: 'Nova lista',
        ]);

        $this->redirect(route('lists.show', $list), navigate: true);
    }

    public function createFromTemplate(int $templateId): void
    {
        $template = Template::where('user_id', Auth::id())
            ->findOrFail($templateId);

        $list = Auth::user()->shoppingLists()->create([
            'name' => $template->name,
        ]);

        $items = $template->items()->get();
        $sortOrder = 0;

        foreach ($items as $item) {
            $list->listItems()->create([
                'item_id' => $item->id,
                'quantity' => $item->last_quantity ?? 1,
                'unit' => $item->last_unit ?? $item->default_unit,
                'sort_order' => $sortOrder++,
            ]);
        }

        $this->redirect(route('lists.show', $list), navigate: true);
    }

    public function deleteList(string $listId): void
    {
        ShoppingList::where('user_id', Auth::id())
            ->where('id', $listId)
            ->delete();
    }

    public function render()
    {
        $lists = Auth::user()->shoppingLists()
            ->withCount('listItems')
            ->orderByDesc('updated_at')
            ->limit(20)
            ->get();

        $templates = Auth::user()->templates()
            ->withCount('items')
            ->limit(4)
            ->get();

        return view('livewire.lists.index', compact('lists', 'templates'));
    }
}
