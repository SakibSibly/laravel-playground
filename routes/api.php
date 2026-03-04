<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;

Route::apiResource('/items', ItemController::class);

Route::get('/health', function () {
    return response()->json(['status' => 'ok']);
});

Route::fallback(function () {
    return response()->json(['message' => 'Not Found'], 404);
});
