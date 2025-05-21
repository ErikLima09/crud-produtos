<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProdutoController;
use App\Http\Controllers\Api\CategoriaController;


Route::get('/', function () {
    return view('welcome');
});

/*Route::prefix('api')->middleware('api')->group(function () {
    Route::apiResource('produtos', ProdutoController::class);
    Route::apiResource('categorias', CategoriaController::class);
});*/



