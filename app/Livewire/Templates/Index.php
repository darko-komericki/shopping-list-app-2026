<?php

namespace App\Livewire\Templates;

use App\Models\Template;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Index extends Component
{
    public bool $showModal = false;
    public bool $showViewModal = false;
    public ?int $editingId = null;

    // Form fields
    public string $formName = '';
    public string $formDescription = '';
    public string $formColor = 'gray';
    public array $formItemIds = [];

    // View modal
    public ?string $viewName = '';
    public array $viewItems = [];

    public function openCreateModal(): void
    {
        $this->reset(['editingId', 'formName', 'formDescription', 'formItemIds']);
        $this->formColor = 'gray';
        $this->showModal = true;
    }

    public function openEditModal(int $id): void
    {
        $template = Template::where('user_id', Auth::id())->findOrFail($id);

        $this->editingId = $template->id;
        $this->formName = $template->name;
        $this->formDescription = $template->description ?? '';
        $this->formColor = $template->color;
        $this->formItemIds = $template->items()->pluck('items.id')->all();
        $this->showModal = true;
    }

    public function save(string $color, array $itemIds): void
    {
        $this->formColor = $color;
        $this->formItemIds = array_map('intval', $itemIds);

        $this->validate([
            'formName' => 'required|string|max:255',
            'formDescription' => 'nullable|string|max:1000',
            'formColor' => 'required|string|max:20',
        ]);

        if ($this->editingId) {
            $template = Template::where('user_id', Auth::id())->findOrFail($this->editingId);
            $template->update([
                'name' => $this->formName,
                'description' => $this->formDescription ?: null,
                'color' => $this->formColor,
            ]);
        } else {
            $template = Auth::user()->templates()->create([
                'name' => $this->formName,
                'description' => $this->formDescription ?: null,
                'color' => $this->formColor,
            ]);
        }

        // Sync items
        $template->items()->sync($this->formItemIds);

        $this->showModal = false;
    }

    public function deleteTemplate(int $id): void
    {
        $template = Template::where('user_id', Auth::id())->findOrFail($id);
        $template->items()->detach();
        $template->delete();
    }

    public function viewTemplate(int $id): void
    {
        $template = Template::where('user_id', Auth::id())
            ->with('items')
            ->findOrFail($id);

        $this->viewName = $template->name;
        $this->viewItems = $template->items->map(fn ($item) => [
            'name' => $item->name,
            'category' => $item->category,
        ])->all();
        $this->showViewModal = true;
    }

    #[Computed]
    public function templates()
    {
        return Auth::user()->templates()
            ->withCount('items')
            ->get();
    }

    #[Computed]
    public function allItems()
    {
        return Auth::user()->items()
            ->orderBy('name')
            ->get(['id', 'name', 'category']);
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
        return view('livewire.templates.index');
    }
}
