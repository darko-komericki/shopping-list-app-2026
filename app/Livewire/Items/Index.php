<?php

namespace App\Livewire\Items;

use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Index extends Component
{
    public string $search = '';

    // Modal state
    public bool $showModal = false;
    public ?int $editingId = null;
    public string $formName = '';
    public string $formCategory = 'ostalo';
    public string $formUnit = 'kom';

    public function openCreateModal(): void
    {
        $this->editingId = null;
        $this->formName = '';
        $this->formCategory = 'ostalo';
        $this->formUnit = 'kom';
        $this->showModal = true;
    }

    public function openEditModal(int $id): void
    {
        $item = Item::where('user_id', Auth::id())->findOrFail($id);
        $this->editingId = $item->id;
        $this->formName = $item->name;
        $this->formCategory = $item->category;
        $this->formUnit = $item->default_unit;
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate([
            'formName' => 'required|string|max:255',
            'formCategory' => 'required|string|max:100',
            'formUnit' => 'required|string|max:20',
        ]);

        if ($this->editingId) {
            $item = Item::where('user_id', Auth::id())->findOrFail($this->editingId);
            $item->update([
                'name' => $this->formName,
                'category' => $this->formCategory,
                'default_unit' => $this->formUnit,
            ]);
        } else {
            Auth::user()->items()->create([
                'name' => $this->formName,
                'category' => $this->formCategory,
                'default_unit' => $this->formUnit,
            ]);
        }

        $this->showModal = false;
        $this->editingId = null;
    }

    public function deleteItem(int $id): void
    {
        Item::where('user_id', Auth::id())->where('id', $id)->delete();
    }

    #[Computed]
    public function categories(): array
    {
        return Auth::user()->categories()
            ->orderBy('sort_order')
            ->pluck('name')
            ->all();
    }

    #[Computed]
    public function itemsByCategory(): array
    {
        $query = Item::where('user_id', Auth::id());

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        $items = $query->orderBy('name')->get();

        $grouped = [];
        foreach ($this->categories as $cat) {
            $catItems = $items->where('category', $cat)->values();
            if ($catItems->isNotEmpty()) {
                $grouped[$cat] = $catItems;
            }
        }

        return $grouped;
    }

    public function render()
    {
        return view('livewire.items.index');
    }
}
