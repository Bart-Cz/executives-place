<?php

use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/{user}', [UserController::class, 'show'])->name('show');

Route::post('/', [UserController::class, 'store'])->name('store');

Route::put('/{user}', [UserController::class, 'update'])->name('update');

Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
