<?php

namespace App\Http\Controllers;

use App\Models\DetallePedido;
use Illuminate\Http\Request;

class DetallePedidoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $detalles = DetallePedido::with('pedido','producto')->get();
        return response()->json($detalles,200);
    }

    /**
     * Store a newly created resource in storage.
     */
public function store(Request $request)
{
    try {
        $request->validate([
            'id_pedido' => 'required|exists:pedido,id_pedido',
            'id_producto' => 'required|exists:producto,id_producto',
            'cantidad' => 'required|integer|min:1',
            'subtotal' => 'required|numeric|min:0.01'
        ]);

        $detalle = DetallePedido::create($request->all());
        $detalle->load('pedido', 'producto');

        return response()->json($detalle, 201);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Error al crear el detalle',
            'error' => $e->getMessage()
        ], 400);
    }
}


    /**
     * Display the specified resource.
     */
    public function show(DetallePedido $detalle)
{
    try {
        $detalle->load('pedido', 'producto');
        return response()->json($detalle, 200);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Error al obtener el detalle del pedido',
            'error' => $e->getMessage()
        ], 400);
    }
}


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DetallePedido $detalle)
    {
        $request->validate([
            'id_pedido' => 'required|exists:pedido,id_pedido',
            'id_producto' => 'required|exists:producto,id_producto',
            'cantidad' => 'required|integer|min:1',
            'subtotal' => 'required|numeric|min:0.01'
        ]);

        $detalle->update($request->all());
        
        return response()->json([
            'message' => 'Detalle de pedido actualizado correctamente',
            'data' => $detalle
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DetallePedido $detalle)
    {
        $detalle->delete();

        return response()->json([
            'message' => 'Detalle de pedido eliminado correctamente'
        ], 200);
    }
}
