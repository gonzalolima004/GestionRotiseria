<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use Illuminate\Http\Request;

class VentaController extends Controller
{
    /**
     * Mostrar todas las ventas
     */
    public function index()
    {
        $ventas = Venta::with('pedido')->get();
        return response()->json($ventas);
    }

    /**
     * Crear una nueva venta
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'fecha'       => 'required|date',
                'monto_venta' => 'required|numeric|min:0',
                'id_pedido'   => 'required|exists:pedido,id_pedido',
            ]);

            $venta = Venta::create($request->all());

            return response()->json($venta, 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al crear la venta',
                'error'   => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Mostrar una venta específica
     */
    public function show(Venta $venta)
    {
        try {
            $venta->load('pedido');
            return response()->json($venta, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener la venta',
                'error'   => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Actualizar una venta
     */
    public function update(Request $request, Venta $venta)
    {
        $request->validate([
            'fecha'       => 'sometimes|required|date',
            'monto_venta' => 'sometimes|required|numeric|min:0',
            'id_pedido'   => 'sometimes|required|exists:pedido,id_pedido',
        ]);

        $venta->update($request->all());

        return response()->json([
            'message' => 'Venta actualizada correctamente',
            'data'    => $venta
        ], 200);
    }

    /**
     * Eliminar una venta
     */
    public function destroy(Venta $venta)
    {
        $venta->delete();

        return response()->json([
            'message' => 'Venta eliminada correctamente'
        ], 200);
    }
}
