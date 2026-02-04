<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ShoppingList extends Model
{
    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'user_id',
    ];

    protected static function booted(): void
    {
        static::creating(function (ShoppingList $list) {
            if (! $list->id) {
                $list->id = Str::random(21);
            }
        });
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function listItems(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ListItem::class);
    }

    public function purchaseHistory(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PurchaseHistory::class);
    }
}
