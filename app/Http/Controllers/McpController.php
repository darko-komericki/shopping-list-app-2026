<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ListItem;
use App\Models\ShoppingList;
use App\Models\Template;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class McpController extends Controller
{
    public function getShoppingLists(): JsonResponse
    {
        $lists = Auth::user()->shoppingLists()
            ->withCount('listItems')
            ->orderByDesc('updated_at')
            ->limit(20)
            ->get();

        return response()->json($lists);
    }

    public function getShoppingList(ShoppingList $list): JsonResponse
    {
        abort_if($list->user_id !== Auth::id(), 404);

        $list->load('listItems.item');

        return response()->json($list);
    }

    public function getItems(): JsonResponse
    {
        $items = Auth::user()->items()->orderBy('name')->get();

        return response()->json($items);
    }

    public function getTemplates(): JsonResponse
    {
        $templates = Auth::user()->templates()
            ->with('items')
            ->orderBy('name')
            ->get();

        return response()->json($templates);
    }

    public function createShoppingList(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'template_id' => 'nullable|integer|exists:templates,id',
        ]);

        if ($request->template_id) {
            $template = Template::where('user_id', Auth::id())
                ->findOrFail($request->template_id);

            $list = Auth::user()->shoppingLists()->create([
                'name' => $template->name,
            ]);

            $sortOrder = 0;
            foreach ($template->items as $item) {
                $list->listItems()->create([
                    'item_id' => $item->id,
                    'quantity' => $item->last_quantity ?? 1,
                    'unit' => $item->last_unit ?? $item->default_unit,
                    'sort_order' => $sortOrder++,
                ]);
            }
        } else {
            $list = Auth::user()->shoppingLists()->create([
                'name' => $request->input('name', 'Nova lista'),
            ]);
        }

        $list->load('listItems.item');

        return response()->json($list, 201);
    }

    public function addItemToList(Request $request, ShoppingList $list): JsonResponse
    {
        abort_if($list->user_id !== Auth::id(), 404);

        $request->validate([
            'item_id' => 'required|integer|exists:items,id',
        ]);

        $item = Item::where('user_id', Auth::id())->findOrFail($request->item_id);

        if ($list->listItems()->where('item_id', $item->id)->exists()) {
            return response()->json(['message' => 'Artikl je već na listi.'], 409);
        }

        $maxSort = $list->listItems()->max('sort_order') ?? -1;

        $listItem = $list->listItems()->create([
            'item_id' => $item->id,
            'quantity' => $item->last_quantity ?? 1,
            'unit' => $item->last_unit ?? $item->default_unit,
            'sort_order' => $maxSort + 1,
        ]);

        $list->touch();
        $listItem->load('item');

        return response()->json($listItem, 201);
    }

    public function createAndAddItem(Request $request, ShoppingList $list): JsonResponse
    {
        abort_if($list->user_id !== Auth::id(), 404);

        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'unit' => 'required|string|max:50',
        ]);

        $item = Auth::user()->items()->create([
            'name' => $request->name,
            'category' => $request->category,
            'default_unit' => $request->unit,
        ]);

        $maxSort = $list->listItems()->max('sort_order') ?? -1;

        $listItem = $list->listItems()->create([
            'item_id' => $item->id,
            'quantity' => 1,
            'unit' => $request->unit,
            'sort_order' => $maxSort + 1,
        ]);

        $list->touch();
        $listItem->load('item');

        return response()->json($listItem, 201);
    }

    public function toggleListItem(ListItem $listItem): JsonResponse
    {
        abort_if($listItem->shoppingList->user_id !== Auth::id(), 404);

        $listItem->update(['checked' => ! $listItem->checked]);
        $listItem->shoppingList->touch();

        return response()->json($listItem);
    }

    public function removeItemFromList(ListItem $listItem): JsonResponse
    {
        abort_if($listItem->shoppingList->user_id !== Auth::id(), 404);

        $listItem->delete();
        $listItem->shoppingList->touch();

        return response()->json(null, 204);
    }

    public function clearCheckedItems(ShoppingList $list): JsonResponse
    {
        abort_if($list->user_id !== Auth::id(), 404);

        $count = $list->listItems()->where('checked', true)->delete();
        $list->touch();

        return response()->json(['deleted' => $count]);
    }

    public function deleteShoppingList(ShoppingList $list): JsonResponse
    {
        abort_if($list->user_id !== Auth::id(), 404);

        $list->delete();

        return response()->json(null, 204);
    }
}
