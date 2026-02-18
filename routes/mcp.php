<?php

use App\Http\Controllers\McpController;
use Illuminate\Support\Facades\Route;

Route::get('/lists', [McpController::class, 'getShoppingLists']);
Route::get('/lists/{list}', [McpController::class, 'getShoppingList']);
Route::get('/items', [McpController::class, 'getItems']);
Route::get('/templates', [McpController::class, 'getTemplates']);

Route::post('/lists', [McpController::class, 'createShoppingList']);
Route::post('/lists/{list}/items', [McpController::class, 'addItemToList']);
Route::post('/lists/{list}/items/create', [McpController::class, 'createAndAddItem']);

Route::patch('/list-items/{listItem}/toggle', [McpController::class, 'toggleListItem']);

Route::delete('/list-items/{listItem}', [McpController::class, 'removeItemFromList']);
Route::delete('/lists/{list}/checked', [McpController::class, 'clearCheckedItems']);
Route::delete('/lists/{list}', [McpController::class, 'deleteShoppingList']);
