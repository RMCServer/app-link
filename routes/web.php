<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SavedItemController;
use App\Http\Controllers\AccountSwitchController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SearchController;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});






// eigen dingen

Route::middleware(['auth'])->group(function () {
    Route::get('saved-items', [SavedItemController::class, 'index'])->name('index');
    Route::get('library', [SavedItemController::class, 'mediaLibrary'])->name('library');
    Route::get('saved-items/new', [SavedItemController::class, 'create'])->name('create');
    Route::post('saved-items/store', [SavedItemController::class, 'store'])->name('saved-items.store');
    Route::get('/saved-items/{savedItem}', [SavedItemController::class, 'show'])
            ->name('show');
    Route::get('/saved-items/{savedItem}/edit', [SavedItemController::class, 'edit'])
                ->name('edit');
    Route::put('/saved-items/{savedItem}/update', [SavedItemController::class, 'update'])
                    ->name('update');

    Route::delete('/saved-items/{savedItem}', [SavedItemController::class, 'delete'])
        ->name('delete');


    Route::get('/saved-items/{savedItem}/image', [SavedItemController::class, 'image'])
        ->name('saved-items.image');
    Route::get('/saved-items/{savedItem}/view', [SavedItemController::class, 'view'])
        ->name('saved-items.view');
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

Route::middleware(['auth'])->group(function () {
    Route::get('/settings', [SettingsController::class, 'index'])
        ->name('settings.index');

    Route::delete('/settings/saved-data', [SettingsController::class, 'deleteSavedData'])
        ->name('settings.saved-data.delete');

    Route::delete('/settings/account', [SettingsController::class, 'deleteAccount'])
        ->name('settings.account.delete');

    Route::post('/logout', [SettingsController::class, 'logout'])
        ->name('logout');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/search', [SearchController::class, 'index'])
        ->name('search.index');
});

require __DIR__.'/settings.php';
