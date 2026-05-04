<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SavedItemController;
use App\Http\Controllers\AccountSwitchController;
use App\Http\Controllers\CategoryController;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});






// eigen dingen

Route::middleware(['auth'])->group(function () {
    Route::get('saved-items', [SavedItemController::class, 'index'])->name('index');
    Route::get('saved-items/new', [SavedItemController::class, 'create'])->name('create');
    Route::post('saved-items/store', [SavedItemController::class, 'store'])->name('saved-items.store');
    Route::get('/saved-items/{savedItem}', [SavedItemController::class, 'show'])
            ->name('show');

    Route::delete('/saved-items/{savedItem}', [SavedItemController::class, 'delete'])
        ->name('delete');
});

Route::middleware(['auth'])->group(function () {
    Route::resource('categories', CategoryController::class);
});

Route::middleware(['auth'])->group(function () {
    Route::get('/accounts/switch', [AccountSwitchController::class, 'index'])
        ->name('accounts.switch.index');

    Route::post('/accounts/{account}/switch', [AccountSwitchController::class, 'switch'])
        ->name('accounts.switch');
});

require __DIR__.'/settings.php';
