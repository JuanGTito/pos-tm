<?php

use App\Http\Controllers\BusinessController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductPurchaseController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', DashboardController::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Demo de componentes atomizados
Route::get('/components-demo', function () {
    return view('components-demo');
})->middleware(['auth'])->name('components.demo');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('products', ProductController::class)->except(['show']);
    Route::resource('sales', SalesController::class);
    Route::resource('users', UserController::class);
    Route::resource('expenses', ExpenseController::class)->except(['show']);
    Route::get('/my-business', [BusinessController::class, 'edit'])->name('business.edit');
    Route::put('/my-business', [BusinessController::class, 'update'])->name('business.update');
    Route::get('/inventory-entries', [ProductPurchaseController::class, 'index'])->name('inventory-entries.index');
    Route::get('/inventory-entries/{inventoryEntry}', [ProductPurchaseController::class, 'show'])->name('inventory-entries.show');

    // API routes for dynamic search
    Route::get('/api/search-customers', [SalesController::class, 'searchCustomers'])->name('api.search-customers');
    Route::get('/api/search-products', [SalesController::class, 'searchProducts'])->name('api.search-products');
});

require __DIR__.'/auth.php';
