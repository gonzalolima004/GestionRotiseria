<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use Illuminate\Http\Request;
use App\Services\WhatsAppService;
use App\Models\Cliente;

class PedidoController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/pedidos",
     *     summary="Obtener lista de pedidos",
     *     description="Requiere autenticación con token JWT.",
     *     tags={"Pedidos"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Lista de pedidos obtenida correctamente"),
     *     @OA\Response(response=404, description="No se pudieron obtener los pedidos")
     * )
     */
  public function index()
{
    try {
        $pedidos = Pedido::with([
            'cliente',
            'metodoPago',
            'estado',
            'modalidad',
            'detalles.producto'
        ])->get();

        foreach ($pedidos as $pedido) {
            $agrupados = [];

            // 🔹 Recorremos todos los detalles
            foreach ($pedido->detalles as $detalle) {
                if ($detalle->producto) {
                    $idProd = $detalle->producto->id_producto;

                    // Si ya existe este producto en el array, acumulamos cantidad y subtotal
                    if (isset($agrupados[$idProd])) {
                        $agrupados[$idProd]['cantidad'] += $detalle->cantidad;
                        $agrupados[$idProd]['subtotal'] += $detalle->producto->precio_producto * $detalle->cantidad;
                    } else {
                        // Si no existe aún, lo agregamos
                        $agrupados[$idProd] = [
                            'producto' => $detalle->producto,
                            'cantidad' => $detalle->cantidad,
                            'subtotal' => $detalle->producto->precio_producto * $detalle->cantidad
                        ];
                    }
                }
            }

            // 🔹 Calculamos el total del pedido
            $pedido->monto_total = collect($agrupados)->sum('subtotal');

            // 🔹 Reemplazamos los detalles originales por los agrupados
            $pedido->detalles = array_values($agrupados);
        }

        return response()->json($pedidos, 200);

    } catch (\Exception $e) {
        return response()->json([
            'message' => 'No se pudieron obtener los pedidos',
            'error' => $e->getMessage()
        ], 404);
    }
}



    /**
     * @OA\Post(
     *     path="/api/pedidos",
     *     summary="Registrar un nuevo pedido",
     *     description="Crea un pedido nuevo",
     *     tags={"Pedidos"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"fecha_hora", "monto_total", "dni_cliente", "id_metodo_pago", "id_estado_pedido", "id_modalidad_entrega"},
     *             @OA\Property(property="fecha_hora", type="string", format="date-time", example="2025-10-06T14:30:00"),
     *             @OA\Property(property="monto_total", type="number", format="float", example=2500.50),
     *             @OA\Property(property="dni_cliente", type="string", example="12345678"),
     *             @OA\Property(property="id_metodo_pago", type="integer", example=1),
     *             @OA\Property(property="id_estado_pedido", type="integer", example=2),
     *             @OA\Property(property="id_modalidad_entrega", type="integer", example=2)
     *         )
     *     ),
     *     @OA\Response(response=201, description="Pedido registrado correctamente"),
     *     @OA\Response(response=422, description="Datos inválidos"),
     *     @OA\Response(response=500, description="Error interno")
     * )
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'fecha_hora' => 'required|date',
                'monto_total' => 'required|numeric|min:0',
                'dni_cliente' => 'required|string|exists:cliente,dni_cliente',
                'id_metodo_pago' => 'required|integer|exists:metodo_pago,id_metodo_pago',
                'id_estado_pedido' => 'required|integer|exists:estado_pedido,id_estado_pedido',
                'id_modalidad_entrega' => 'required|integer|exists:modalidad_entrega,id_modalidad_entrega',
            ]);

            $pedido = Pedido::create($request->all());

            return response()->json([
                'message' => 'Pedido registrado correctamente',
                'pedido' => $pedido
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al registrar el pedido',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/pedidos/{id}",
     *     summary="Obtener un pedido por ID",
     *     description="Requiere autenticación con token JWT.",
     *     tags={"Pedidos"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, description="ID del pedido", @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Pedido obtenido correctamente"),
     *     @OA\Response(response=404, description="Pedido no encontrado")
     * )
     */
    public function show($id)
    {
        $pedido = Pedido::with(['cliente', 'metodoPago', 'estado', 'modalidad', 'detalles'])->find($id);

        if (!$pedido) {
            return response()->json(['message' => 'Pedido no encontrado'], 404);
        }

        return response()->json([
                'message' => 'Pedido obtenido correctamente',
                'pedido' => $pedido
            ], 200);
    }

    /**
     * @OA\Put(
     *     path="/api/pedidos/{id}",
     *     summary="Actualizar un pedido existente",
     *     description="Actualiza un pedido por ID. Requiere autenticación con token JWT.",
     *     tags={"Pedidos"},
     *     @OA\Parameter(name="id", in="path", required=true, description="ID del pedido a actualizar", @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"fecha_hora", "monto_total", "dni_cliente", "id_metodo_pago", "id_estado_pedido", "id_modalidad_entrega"},
     *             @OA\Property(property="fecha_hora", type="string", format="date-time", example="2025-10-06T14:30:00"),
     *             @OA\Property(property="monto_total", type="number", format="float", example=2500.50),
     *             @OA\Property(property="dni_cliente", type="string", example="12345678"),
     *             @OA\Property(property="id_metodo_pago", type="integer", example=1),
     *             @OA\Property(property="id_estado_pedido", type="integer", example=2),
     *             @OA\Property(property="id_modalidad_entrega", type="integer", example=2),
     *             @OA\Property(property="tiempo_estimado", type="string", example="30 minutos")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Pedido actualizado correctamente"),
     *     @OA\Response(response=404, description="Pedido no encontrado"),
     *     @OA\Response(response=400, description="Error al actualizar el pedido")
     * )
     */
    public function update(Request $request, $id, WhatsAppService $whatsapp)
    {
        $pedido = Pedido::find($id);

        if (!$pedido) {
            return response()->json(['message' => 'Pedido no encontrado'], 404);
        }

        try {
            $validated = $request->validate([
                'fecha_hora' => 'sometimes|date',
                'monto_total' => 'sometimes|numeric|min:0',
                'dni_cliente' => 'sometimes|string|exists:cliente,dni_cliente',
                'id_metodo_pago' => 'sometimes|integer|exists:metodo_pago,id_metodo_pago',
                'id_estado_pedido' => 'sometimes|integer|exists:estado_pedido,id_estado_pedido',
                'id_modalidad_entrega' => 'sometimes|integer|exists:modalidad_entrega,id_modalidad_entrega',
                'tiempo_estimado' => 'sometimes|string|max:50',
            ]);

            $pedido->update(collect($validated)->except('tiempo_estimado')->toArray());

            if (isset($validated['id_estado_pedido']) && $validated['id_estado_pedido'] == 2) {
                $cliente = Cliente::where('dni_cliente', $pedido->dni_cliente)->first();

                if ($cliente && $cliente->telefono_cliente) {
                    $tiempo = $validated['tiempo_estimado'] ?? 'unos minutos';
                    $mensaje = "¡Hola {$cliente->nombre_cliente}! Tu pedido N°{$pedido->id_pedido} fue CONFIRMADO. Estará listo en {$tiempo} aproximadamente. ¡Muchas gracias!";

                    $response = $whatsapp->enviarMensaje($cliente->telefono_cliente, $mensaje);
                    \Log::info("Mensaje enviado a WhatsApp:", $response);
                }
            }

            return response()->json([
                'message' => 'Pedido actualizado correctamente',
                'pedido' => $pedido
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar el pedido',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/pedidos/{id}",
     *     summary="Eliminar un pedido",
     *     description="Elimina un pedido por ID. Requiere autenticación con token JWT.",
     *     tags={"Pedidos"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, description="ID del pedido a eliminar", @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Pedido eliminado correctamente"),
     *     @OA\Response(response=404, description="Pedido no encontrado"),
     *     @OA\Response(response=400, description="Error al eliminar el pedido")
     * )
     */
    public function destroy($id)
    {
        $pedido = Pedido::find($id);

        if (!$pedido) {
            return response()->json(['message' => 'Pedido no encontrado'], 404);
        }

        try {
            $pedido->delete();

            return response()->json([
                'message' => 'Pedido eliminado correctamente (si posee detalles_pedido, estos también se eliminan)',
                'pedido' => $id
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al eliminar el pedido',
                'error' => $e->getMessage()
            ], 400);
        }
    }
}
