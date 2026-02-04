<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class TemplateItem extends Pivot
{
    protected $table = 'template_items';

    public $timestamps = false;

    protected $fillable = [
        'template_id',
        'item_id',
    ];
}
