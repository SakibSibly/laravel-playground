<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\VendorController;

Route::apiResource('/items', ItemController::class);
Route::apiResource('/vendors', VendorController::class);