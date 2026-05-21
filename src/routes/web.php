<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TodoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
        return view('login');
    });

    // Authentication routes
    Route::middleware('guest')->group(function () {
        Route::get('/login', [LoginController::class, 'index'])->name('login');
        Route::post('/login', [LoginController::class, 'store']);
        Route::get('/register', [RegisterController::class, 'index'])->name('register');
        Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
    });

Route::middleware('auth')->group(function () {
        // dashboard route
     Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // category routes
    Route::prefix('category')->name('category.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::get('/create', [CategoryController::class, 'create'])->name('create');
        Route::post('/store', [CategoryController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [CategoryController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [CategoryController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [CategoryController::class, 'destroy'])->name('delete');
    });

    // Todo route
    Route::prefix('todo')->name('todo.')->group(function () {
    Route::get('/', [TodoController::class, 'index'])->name('index');
    Route::get('/create', [TodoController::class, 'create'])->name('create');
    Route::post('/store', [TodoController::class, 'store'])->name('store');
    Route::get('/edit/{id}', [TodoController::class, 'edit'])->name('edit');
    Route::put('/update/{id}', [TodoController::class, 'update'])->name('update');
    Route::patch('/status/{id}', [TodoController::class, 'updateStatus'])->name('status');
    Route::delete('/delete/{id}', [TodoController::class, 'destroy'])->name('delete');
    });

});

// Logout route
Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');