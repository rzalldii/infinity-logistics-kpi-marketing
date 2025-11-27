<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RateController;
use App\Http\Controllers\ShipperController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\AuthController;

Route::get('/laravel', function () {
    return view('welcome');
});

Route::get('login', [AuthController::class, 'index'])->name('login');
Route::post('post-login', [AuthController::class, 'postLogin'])->name('login.post');

Route::middleware('auth')->group(function () {
    Route::get('/', [AuthController::class, 'dashboard'])->name('dashboard');
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('rates', [RateController::class, 'index'])->name('rates.index');
    Route::get('rates/{id}/edit', [RateController::class, 'edit'])->name('rates.edit');

    Route::middleware('role:super_admin,admin,marketing')->group(function () {
        Route::post('rates', [RateController::class, 'store'])->name('rates.store');
        Route::put('rates/{id}', [RateController::class, 'update'])->name('rates.update');
        Route::delete('rates/{id}', [RateController::class, 'destroy'])->name('rates.destroy');
    });

    Route::get('shippers', [ShipperController::class, 'index'])->name('shippers.index');
    Route::get('shippers/{id}/edit', [ShipperController::class, 'edit'])->name('shippers.edit');

    Route::middleware('role:super_admin,admin,marketing')->group(function () {
        Route::post('shippers', [ShipperController::class, 'store'])->name('shippers.store');
        Route::put('shippers/{id}', [ShipperController::class, 'update'])->name('shippers.update');
        Route::delete('shippers/{id}', [ShipperController::class, 'destroy'])->name('shippers.destroy');
    });

    Route::middleware('role:super_admin,admin,marketing')->group(function () {
        Route::get('activities', [ActivityController::class, 'index'])->name('activities.index');
        Route::post('activities', [ActivityController::class, 'store'])->name('activities.store');
        Route::get('activities/{id}/edit', [ActivityController::class, 'edit'])->name('activities.edit');
        Route::put('activities/{id}', [ActivityController::class, 'update'])->name('activities.update');
        Route::delete('activities/{id}', [ActivityController::class, 'destroy'])->name('activities.destroy');
    });

    Route::middleware('role:super_admin')->group(function () {
        Route::get('users', [UserController::class, 'index'])->name('users.index');
        Route::post('users', [UserController::class, 'store'])->name('users.store');
        Route::get('users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('users/{id}', [UserController::class, 'update'])->name('users.update');
        Route::delete('users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    });
});