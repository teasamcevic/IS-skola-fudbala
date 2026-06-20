<?php

use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\SelekcijaApiController;
use App\Http\Controllers\Api\TrenerApiController;
use App\Http\Controllers\Api\TreningApiController;
use Illuminate\Support\Facades\Route;

// API autentifikacija je odvojena od postojećih Blade/web ruta.
Route::middleware('web')->group(function () {
    Route::post('/register', [AuthApiController::class, 'register']);
    Route::post('/login', [AuthApiController::class, 'login']);
    Route::post('/logout', [AuthApiController::class, 'logout'])->middleware('auth:sanctum');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthApiController::class, 'user']);

    Route::get('/selekcije', SelekcijaApiController::class);
    Route::get('/treneri', TrenerApiController::class);
    Route::apiResource('treninzi', TreningApiController::class);
});
