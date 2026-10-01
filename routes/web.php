<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Demo de componentes atomizados
Route::get('/components-demo', function () {
    return view('components-demo');
})->middleware(['auth'])->name('components.demo');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    Route::resource('products', ProductController::class);
    Route::resource('sales', SalesController::class);
    Route::resource('users', UserController::class);

    // API routes for dynamic search
    Route::get('/api/search-customers', [SalesController::class, 'searchCustomers'])->name('api.search-customers');
    Route::get('/api/search-products', [SalesController::class, 'searchProducts'])->name('api.search-products');
});


require __DIR__.'/auth.php';
