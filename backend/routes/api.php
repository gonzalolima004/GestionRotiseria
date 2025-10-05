<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\DetallePedidoController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\VentaController;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::apiResource('/categorias', CategoriaController::class)->only(['index', 'show']);

Route::apiResource('/productos', ProductoController::class)->only(['index', 'show']);

Route::apiResource('/detalle_pedidos', DetallePedidoController::class);

Route::post('/clientes', [ClienteController::class, 'store']);
Route::put('/clientes/{cliente}', [ClienteController::class, 'update']);

Route::group(['middleware' => ['jwt.auth']], function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::apiResource('/clientes', ClienteController::class)->only(['index', 'show', 'destroy']);

    Route::apiResource('/productos', ProductoController::class)->except(['index', 'show']);

    Route::apiResource('/categorias', CategoriaController::class)->except(['index', 'show']);

    Route::apiResource('/ventas', VentaController::class);
    Route::apiResource('/pedidos', PedidoController::class);
    Route::put('/pedidos/finalizar', [PedidoController::class, 'finalizarPedido']);



    /* Route::apiResource('/clientes', App\Http\Controllers\ClienteController::class); */
    /* Route::apiResource('/categorias', App\Http\Controllers\CategoriaController::class); */
    /* Route::apiResource('/productos', App\Http\Controllers\ProductoController::class); */
    /* Route::apiResource('/ventas', App\Http\Controllers\VentaController::class);
    Route::apiResource('/pedidos', App\Http\Controllers\PedidoController::class);*/
});