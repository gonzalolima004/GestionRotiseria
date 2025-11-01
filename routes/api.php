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

// Rutas públicas
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::apiResource('/categorias', CategoriaController::class)->only(['index', 'show']);
Route::apiResource('/productos', ProductoController::class)->only(['index', 'show']);
Route::apiResource('/detalle_pedidos', DetallePedidoController::class);

Route::post('/pedidos', [PedidoController::class, 'store']);
Route::put('/pedidos/{pedido}', [PedidoController::class, 'update']);
Route::apiResource('/clientes', ClienteController::class);

// Rutas protegidas
Route::group(['middleware' => ['jwt.auth']], function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    
    Route::apiResource('/categorias', CategoriaController::class)->except(['index', 'show']);
    Route::apiResource('/productos', ProductoController::class)->except(['index', 'show']);
    Route::apiResource('/pedidos', PedidoController::class)->except(['store', 'update']);
    Route::apiResource('/ventas', VentaController::class);

});
