<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use Illuminate\Http\Request;

class PedidoController extends Controller
{
    // Listar pedidos
    public function index()
    {
        return Pedido::all();
    }

    // Buscar pedido por ID
    public function show($id)
    {
        $pedido = Pedido::with(['cliente', 'metodoPago', 'estado', 'modalidad', 'detalles.producto'])
            ->find($id);

        if (!$pedido) {
            return response()->json(['error' => 'Pedido no encontrado'], 404);
        }

        return $pedido;
    }

    // Crear pedido
    public function store(Request $request)
    {
        $data = $request->validate([
            'fecha_hora' => 'required|date',
            'monto_total' => 'required|numeric',
            'dni_cliente' => 'required',
            'id_metodo_pago' => 'required|integer',
            'id_estado_pedido' => 'required|integer',
            'id_modalidad_entrega' => 'required|integer',
        ]);

        $pedido = Pedido::create($data);

        return response()->json($pedido, 201);
    }

    // Actualizar pedido
    // Actualizar pedido
    public function update(Request $request, $id)
    {
        $pedido = Pedido::find($id);

        if (!$pedido) {
            return response()->json(['error' => 'Pedido no encontrado'], 404);
        }

        $data = $request->validate([
            'fecha_hora' => 'sometimes|date',
            'monto_total' => 'sometimes|numeric',
            'dni_cliente' => 'sometimes|string|exists:cliente,dni_cliente',
            'id_metodo_pago' => 'sometimes|integer|exists:metodo_pago,id_metodo_pago',
            'id_estado_pedido' => 'sometimes|integer|exists:estado_pedido,id_estado_pedido',
            'id_modalidad_entrega' => 'sometimes|integer|exists:modalidad_entrega,id_modalidad_entrega',
        ]);

        $pedido->update($data);

        return response()->json([
            'message' => 'Pedido actualizado correctamente',
            'pedido' => $pedido
        ]);
    }

    // Eliminar pedido
    public function destroy($id)
    {
        $pedido = Pedido::find($id);

        if (!$pedido) {
            return response()->json(['error' => 'Pedido no encontrado'], 404);
        }

        $pedido->delete();

        return response()->json(['message' => 'Pedido eliminado']);
    }
}
