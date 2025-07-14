<?php

use Illuminate\Support\Facades\Route;
use Modules\Order\Http\Controllers\Api\OrderController;

Route::middleware('auth')->prefix('orders')->group(function () {
    Route::post('/', [OrderController::class, 'store']);
});

