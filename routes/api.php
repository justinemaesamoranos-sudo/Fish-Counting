<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FishCountController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/*
|--------------------------------------------------------------------------
| Fish Counting IoT API
|--------------------------------------------------------------------------
| Raspberry Pi sends fish counts here
*/
Route::post('/fish-count', [FishCountController::class, 'store']);
