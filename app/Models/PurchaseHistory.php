<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseHistory extends Model
{
    public $timestamps = false;

    protected $table = 'purchase_history';

    protected $fillable = [
        'item_id',
        'shopping_list_id',
        'quantity',
        'purchased_at',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:2',
            'purchased_at' => 'datetime',
        ];
    }

    public function item(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function shoppingList(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ShoppingList::class);
    }
}
