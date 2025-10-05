<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\Cliente;
use App\Models\DetallePedido;
use Illuminate\Http\Request;

class PedidoController extends Controller
{
    // Listar pedidos
    public function index()
{
    return Pedido::with(['detalles', 'cliente', 'metodoPago', 'estado', 'modalidad'])->get();
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

    public function store(Request $request)
{
    try {
        $pedido = Pedido::create([
            'fecha_hora' => now(),
            'monto_total' => 0,
            'id_estado_pedido' => 1,
            'dni_cliente' => null,
            'id_metodo_pago' => 1,
            'id_modalidad_entrega' => 1,
        ]);

        return response()->json($pedido, 201);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

    //Completar campos y actualizar toda la tabla 

    public function finalizarPedido(Request $request)
{
    $data = $request->validate([
        'id_pedido' => 'required|integer',
        'dni_cliente' => 'required|string',
        'nombre_cliente' => 'required|string',
        'telefono_cliente' => 'required|string',
        'direccion_cliente' => 'required|string',
        'id_metodo_pago' => 'required|integer',
        'id_modalidad_entrega' => 'required|integer',
    ]);

    // Verificar que el pedido exista
    $pedido = Pedido::find($data['id_pedido']);
    if (!$pedido) {
        return response()->json(['error' => 'error de pedido no encontrado dentro de finalizarPedido'], 404);
    }

    // Crear cliente si no existe
    Cliente::firstOrCreate(
        ['dni_cliente' => $data['dni_cliente']],
        [
            'nombre_cliente' => $data['nombre_cliente'],
            'telefono_cliente' => $data['telefono_cliente'],
            'direccion_cliente' => $data['direccion_cliente'],
        ]
    );

    // Calcular el monto total del pedido
    $monto = DetallePedido::where('id_pedido', $data['id_pedido'])->sum('subtotal');

    // Actualizar el pedido con los datos del cliente y entrega
    $pedido->update([
        'dni_cliente' => $data['dni_cliente'],
        'monto_total' => $monto,
        'id_metodo_pago' => $data['id_metodo_pago'],
        'id_modalidad_entrega' => $data['id_modalidad_entrega'],
        'id_estado_pedido' => 2, // "confirmado"
    ]);

    // Devolver el pedido completo usando el método show
    return $this->show($data['id_pedido']);
}



    // Actualizar pedido
    public function update(Request $request, $id)
    {
        $pedido = Pedido::find($id);

        if (!$pedido) {
            return response()->json(['error' => 'Pedido no encontrado'], 404);
        }

        $request->validate([
            'fecha_hora' => 'sometimes|date',
            'monto_total' => 'sometimes|numeric',
            'dni_cliente' => 'sometimes|string|exists:cliente,dni_cliente',
            'id_metodo_pago' => 'sometimes|integer|exists:metodo_pago,id_metodo_pago',
            'id_estado_pedido' => 'sometimes|integer|exists:estado_pedido,id_estado_pedido',
            'id_modalidad_entrega' => 'sometimes|integer|exists:modalidad_entrega,id_modalidad_entrega',
        ]);

        $pedido->update($request->all());

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
