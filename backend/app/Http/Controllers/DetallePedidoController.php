<?php

namespace App\Http\Controllers;

use App\Models\DetallePedido;
use Illuminate\Http\Request;

class DetallePedidoController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/detalle_pedidos",
     *     summary="Obtener lista de detalles de pedido",
     *     tags={"DetallePedido"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de detalles obtenida correctamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se pudieron obtener los detalles"
     *     )
     * )
     */
    public function index()
    {
        try {
            $detalles = DetallePedido::all();
            return response()->json($detalles, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'No se pudieron obtener los detalles de pedido',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/detalle_pedidos",
     *     summary="Crear un nuevo detalle de pedido",
     *     description="Crea un detalle de pedido.",
     *     tags={"DetallePedido"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"id_pedido", "id_producto", "cantidad", "subtotal"},
     *             @OA\Property(property="id_pedido", type="integer", example=1),
     *             @OA\Property(property="id_producto", type="integer", example=5),
     *             @OA\Property(property="cantidad", type="integer", example=3),
     *             @OA\Property(property="subtotal", type="number", format="float", example=450.00)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Detalle de pedido creado correctamente"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Datos inválidos"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno"
     *     )
     * )
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'id_pedido' => 'required|integer|exists:pedido,id_pedido',
                'id_producto' => 'required|integer|exists:producto,id_producto',
                'cantidad' => 'required|integer|min:1',
                'subtotal' => 'required|numeric|min:0',
            ]);

            $detalle = DetallePedido::create($request->all());

            return response()->json([
                'message' => 'Detalle de pedido creado correctamente',
                'data' => $detalle
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al crear el detalle de pedido',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/detalle_pedidos/{id}",
     *     summary="Obtener un detalle de pedido por ID",
     *     tags={"DetallePedido"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del detalle de pedido",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalle de pedido obtenido correctamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Detalle de pedido no encontrado"
     *     )
     * )
     */
    public function show($id)
    {
        $detalle = DetallePedido::find($id);

        if (!$detalle) {
            return response()->json([
                'message' => 'Detalle de pedido no encontrado',
            ], 404);
        }

        return response()->json($detalle, 200);
    }

    /**
     * @OA\Put(
     *     path="/api/detalle_pedidos/{id}",
     *     summary="Actualizar un detalle de pedido",
     *     description="Actualiza un detalle de pedido por ID.",
     *     tags={"DetallePedido"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del detalle de pedido a actualizar",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"id_pedido", "id_producto", "cantidad", "subtotal"},
     *             @OA\Property(property="id_pedido", type="integer", example=1),
     *             @OA\Property(property="id_producto", type="integer", example=5),
     *             @OA\Property(property="cantidad", type="integer", example=2),
     *             @OA\Property(property="subtotal", type="number", format="float", example=300.00)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalle de pedido actualizado correctamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Detalle de pedido no encontrado"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error al actualizar el detalle"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $detalle = DetallePedido::find($id);

        if (!$detalle) {
            return response()->json([
                'message' => 'Detalle de pedido no encontrado'
            ], 404);
        }

        try {
            $request->validate([
                'id_pedido' => 'required|integer|exists:pedido,id_pedido',
                'id_producto' => 'required|integer|exists:producto,id_producto',
                'cantidad' => 'required|integer|min:1',
                'subtotal' => 'required|numeric|min:0',
            ]);

            $detalle->update($request->only([
                'id_pedido',
                'id_producto',
                'cantidad',
                'subtotal'
            ]));

            return response()->json([
                'message' => 'Detalle de pedido actualizado correctamente',
                'data' => $detalle
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar el detalle de pedido',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/detalle_pedidos/{id}",
     *     summary="Eliminar un detalle de pedido",
     *     description="Elimina un detalle de pedido por ID.",
     *     tags={"DetallePedido"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del detalle de pedido a eliminar",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalle de pedido eliminado correctamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Detalle de pedido no encontrado"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error al eliminar el detalle"
     *     )
     * )
     */
    public function destroy($id)
    {
        $detalle = DetallePedido::find($id);

        if (!$detalle) {
            return response()->json([
                'message' => 'Detalle de pedido no encontrado'
            ], 404);
        }

        try {
            $detalle->delete();

            return response()->json([
                'message' => 'Detalle de pedido eliminado correctamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al eliminar el detalle de pedido',
                'error' => $e->getMessage()
            ], 400);
        }
    }
}
