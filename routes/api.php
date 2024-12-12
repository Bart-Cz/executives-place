<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:api'])->prefix('user')->name('user.')
    ->group(function () {
        require base_path('routes/api/user.php');
    });
