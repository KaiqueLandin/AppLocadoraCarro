<?php

use App\Http\Controllers\CarroController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\LocacaoController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\ModeloController;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:api')->get('/user',function(Request $request){
    return $request->user();
});

Route::apiResource('cliente',ClienteController::class);

Route::apiResource('carro',CarroController::class);

Route::apiResource('locacao',LocacaoController::class);

Route::apiResource('marca',MarcaController::class);

Route::apiResource('modelo',ModeloController::class);