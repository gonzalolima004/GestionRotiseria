<?php

namespace App\Http\Controllers;

use App\Models\Detalle_Pedido;
use Illuminate\Http\Request;

class DetallePedidoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $detalles = DetallePedido::with('pedido','producto')->get();
        return response()->json($detalles);
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

        $detalles = DetallePedido::create($request->all());
        return response()->json($detalles, 201);
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
    public function show(DetallePedido $detalles)
{
    try {
        $detalles->load('pedido', 'productp');
        return response()->json($detalles, 200);
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
    public function update(Request $request, DetallePedido $detalles)
    {
        $request->validate([
            'id_pedido' => 'required|exists:Pedido,id_pedido',
            'id_producto' => 'required|exists:Producto,id_producto',
            'cantidad' => 'required|integer|min:1',
            'subtotal' => 'required|numeric|min:0.01'
        ]);

        $detalles->update($request->all());
        
        return response()->json([
            'message' => 'Detalle de pedido actualizado correctamente',
            'data' => $detalles
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DetallePedido $detalles)
    {
        $detalles->delete();

        return response()->json([
            'message' => 'Detalle de pedido eliminado correctamente'
        ], 200);
    }
}
