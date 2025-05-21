<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProdutoController;
use App\Http\Controllers\Api\CategoriaController;
use App\Http\Controllers\AuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('produtos', ProdutoController::class);
Route::apiResource('categorias', CategoriaController::class);

// Login
Route::post('/cadastrar', [AuthController::class, 'cadastrar']);
Route::post('/login', [AuthController::class, 'login']);