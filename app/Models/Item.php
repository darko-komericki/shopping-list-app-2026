<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'name',
        'category',
        'default_unit',
        'last_quantity',
        'last_unit',
    ];

    protected function casts(): array
    {
        return [
            'last_quantity' => 'decimal:2',
        ];
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function templates(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Template::class, 'template_items');
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
