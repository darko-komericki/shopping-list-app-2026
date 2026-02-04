<?php

namespace App\Livewire\Categories;

use App\Models\Category;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Index extends Component
{
    public bool $showModal = false;
    public ?int $editingId = null;
    public string $formName = '';
    public string $formColor = 'gray';

    public function openCreateModal(): void
    {
        $this->reset(['editingId', 'formName']);
        $this->formColor = 'gray';
        $this->showModal = true;
    }

    public function openEditModal(int $id): void
    {
        $cat = Category::where('user_id', Auth::id())->findOrFail($id);
        $this->editingId = $cat->id;
        $this->formName = $cat->name;
        $this->formColor = $cat->color;
        $this->showModal = true;
    }

    public function save(string $color): void
    {
        $this->formColor = $color;

        $this->validate([
            'formName' => 'required|string|max:100',
            'formColor' => 'required|string|max:20',
        ]);

        if ($this->editingId) {
            $cat = Category::where('user_id', Auth::id())->findOrFail($this->editingId);
            $cat->update([
                'name' => $this->formName,
                'color' => $this->formColor,
            ]);
        } else {
            $maxSort = Auth::user()->categories()->max('sort_order') ?? -1;
            Auth::user()->categories()->create([
                'name' => $this->formName,
                'color' => $this->formColor,
                'sort_order' => $maxSort + 1,
            ]);
        }

        $this->showModal = false;
    }

    public function deleteCategory(int $id): void
    {
        $cat = Category::where('user_id', Auth::id())->findOrFail($id);

        $itemCount = Item::where('user_id', Auth::id())
            ->where('category', $cat->name)
            ->count();

        if ($itemCount > 0) {
            $this->dispatch('notify', message: "Kategoriju koristi {$itemCount} artikala. Prvo promijenite kategoriju tih artikala.");
            return;
        }

        $cat->delete();
    }

    public function reorder(array $orderedIds): void
    {
        $categories = Auth::user()->categories()->whereIn('id', $orderedIds)->get()->keyBy('id');

        foreach ($orderedIds as $index => $id) {
            if ($cat = $categories->get($id)) {
                $cat->update(['sort_order' => $index]);
            }
        }
    }

    #[Computed]
    public function categories()
    {
        $cats = Auth::user()->categories()
            ->orderBy('sort_order')
            ->get();

        $userId = Auth::id();
        $counts = Item::where('user_id', $userId)
            ->selectRaw('category, count(*) as cnt')
            ->groupBy('category')
            ->pluck('cnt', 'category');

        return $cats->map(function ($cat) use ($counts) {
            $cat->item_count = $counts[$cat->name] ?? 0;
            return $cat;
        });
    }

    public function render()
    {
        return view('livewire.categories.index');
    }
}
