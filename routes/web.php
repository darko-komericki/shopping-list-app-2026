<?php

use App\Models\ShoppingList;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('/', fn () => view('lists.index'))->name('lists.index');
    Route::get('/lists/{list}', fn (ShoppingList $list) => view('lists.show', compact('list')))->name('lists.show');
    Route::get('/items', fn () => view('items.index'))->name('items.index');
    Route::get('/templates', fn () => view('templates.index'))->name('templates.index');
    Route::get('/categories', fn () => view('categories.index'))->name('categories.index');
});

require __DIR__.'/settings.php';
