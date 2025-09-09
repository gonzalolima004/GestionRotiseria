<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::apiResource('/categorias', App\Http\Controllers\CategoriaController::class);
Route::apiResource('/productos', App\Http\Controllers\ProductoController::class);
Route::apiResource('/ventas', App\Http\Controllers\VentaController::class);
Route::apiResource('/pedidos', App\Http\Controllers\PedidoController::class);
Route::apiResource('/detalle_pedidos', App\Http\Controllers\DetallePedidoController::class);

