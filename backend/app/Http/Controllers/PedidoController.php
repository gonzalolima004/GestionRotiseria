<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use Illuminate\Http\Request;

class PedidoController extends Controller
{
    // Listar pedidos
    public function index()
    {
        return Pedido::with(['dni_cliente', 'id_metodo_de_pago', 'id_estado_pedido', 'id_modalidad_de_entrega'])->get();
    }

    // Buscar pedido por ID
    public function show($id)
    {
        $pedido = Pedido::with(['dni_cliente', 'id_metodo_de_pago', 'id_estado_pedido', 'id_modalidad_de_entrega'])
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
            'id_metodo_de_pago' => 'required|integer',
            'id_estado_pedido' => 'required|integer',
            'id_modalidad_de_entrega' => 'required|integer',
        ]);

        $pedido = Pedido::create($data);

        return response()->json($pedido, 201);
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
