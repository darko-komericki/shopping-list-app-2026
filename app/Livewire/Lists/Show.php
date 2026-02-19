<?php

namespace App\Livewire\Lists;

use App\Models\Category;
use App\Models\Item;
use App\Models\ListItem;
use App\Models\ShoppingList;
use App\Models\Template;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;

class Show extends Component
{
    public ShoppingList $list;

    public string $listName = '';
    public bool $editingName = false;
    public bool $showItemPicker = false;
    public bool $showTemplatePicker = false;

    // Item picker state
    public string $search = '';
    public ?string $activeCategory = null;

    public function mount(ShoppingList $list): void
    {
        abort_if($list->user_id !== Auth::id(), 404);

        $this->list = $list;
        $this->listName = $list->name;
    }

    public function saveName(): void
    {
        $this->editingName = false;

        if ($this->listName !== $this->list->name) {
            $this->list->update(['name' => $this->listName]);
        }
    }

    public function toggleItem(int $listItemId): void
    {
        $item = $this->list->listItems()->findOrFail($listItemId);
        $item->update(['checked' => ! $item->checked]);
        $this->list->touch();
    }

    public function updateQuantity(int $listItemId, float $delta): void
    {
        $item = $this->list->listItems()->findOrFail($listItemId);
        $step = self::stepForUnit($item->unit);
        $newQty = max($step, $item->quantity + $delta);
        $item->update(['quantity' => round($newQty, 2)]);
        $this->list->touch();
    }

    public static function stepForUnit(string $unit): float
    {
        return match ($unit) {
            'g' => 50,
            'ml' => 100,
            'kg', 'l' => 0.5,
            default => 1,
        };
    }

    public function removeItem(int $listItemId): void
    {
        $this->list->listItems()->where('id', $listItemId)->delete();
        $this->list->touch();
    }

    public function clearChecked(): void
    {
        $this->list->listItems()->where('checked', true)->delete();
        $this->list->touch();
    }

    public function deleteList(): void
    {
        $this->list->delete();
        $this->redirect(route('lists.index'), navigate: true);
    }

    public function addItem(int $itemId): void
    {
        // Don't add duplicates
        if ($this->list->listItems()->where('item_id', $itemId)->exists()) {
            return;
        }

        $item = Item::where('user_id', Auth::id())->findOrFail($itemId);

        $maxSort = $this->list->listItems()->max('sort_order') ?? -1;

        $unit = $item->last_unit ?? $item->default_unit;
        $this->list->listItems()->create([
            'item_id' => $item->id,
            'quantity' => $item->last_quantity ?? self::stepForUnit($unit),
            'unit' => $unit,
            'sort_order' => $maxSort + 1,
        ]);

        $this->list->touch();
    }

    public function createAndAddItem(string $name, string $category, string $unit): void
    {
        $name = trim($name);
        if (! $name) {
            return;
        }

        $item = Auth::user()->items()->create([
            'name' => $name,
            'category' => $category,
            'default_unit' => $unit,
        ]);

        $this->addItem($item->id);
        $this->search = '';
        $this->showItemPicker = false;
    }

    #[Computed]
    public function uncheckedItems()
    {
        return $this->list->listItems()
            ->with('item')
            ->where('checked', false)
            ->join('items', 'list_items.item_id', '=', 'items.id')
            ->leftJoin('categories', function ($join) {
                $join->on('items.category', '=', 'categories.name')
                    ->where('categories.user_id', Auth::id());
            })
            ->orderBy('categories.sort_order')
            ->orderBy('items.name')
            ->select('list_items.*')
            ->get();
    }

    #[Computed]
    public function checkedItems()
    {
        return $this->list->listItems()
            ->with('item')
            ->where('checked', true)
            ->join('items', 'list_items.item_id', '=', 'items.id')
            ->leftJoin('categories', function ($join) {
                $join->on('items.category', '=', 'categories.name')
                    ->where('categories.user_id', Auth::id());
            })
            ->orderBy('categories.sort_order')
            ->orderBy('items.name')
            ->select('list_items.*')
            ->get();
    }

    #[Computed]
    public function existingItemIds(): array
    {
        return $this->list->listItems()->pluck('item_id')->all();
    }

    #[Computed]
    public function availableItems()
    {
        $query = Item::where('user_id', Auth::id())
            ->whereNotIn('id', $this->existingItemIds);

        if ($this->activeCategory) {
            $query->where('category', $this->activeCategory);
        }

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        return $query->orderBy('name')->get();
    }

    public function addFromTemplate(int $templateId): void
    {
        $template = Template::where('user_id', Auth::id())->findOrFail($templateId);

        $maxSort = $this->list->listItems()->max('sort_order') ?? -1;

        foreach ($template->items as $item) {
            if ($this->list->listItems()->where('item_id', $item->id)->exists()) {
                continue;
            }

            $maxSort++;
            $unit = $item->last_unit ?? $item->default_unit;
            $this->list->listItems()->create([
                'item_id' => $item->id,
                'quantity' => $item->last_quantity ?? self::stepForUnit($unit),
                'unit' => $unit,
                'sort_order' => $maxSort,
            ]);
        }

        $this->list->touch();
        $this->showTemplatePicker = false;
    }

    #[Computed]
    public function templates()
    {
        return Template::where('user_id', Auth::id())
            ->withCount('items')
            ->orderBy('name')
            ->get();
    }

    #[Computed]
    public function categories(): array
    {
        return Auth::user()->categories()
            ->orderBy('sort_order')
            ->pluck('name')
            ->all();
    }

    public function render()
    {
        return view('livewire.lists.show');
    }
}
