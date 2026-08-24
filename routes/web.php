<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/categories', [CategoryController::class, 'index'])->name('categories');
Route::get('/categories/add', [CategoryController::class, 'create'])->name('add_categories');
Route::post('/categories', [CategoryController::class, 'store']);
Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('edit_categories');
Route::put('/categories/{category}', [CategoryController::class, 'update']);
Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);
