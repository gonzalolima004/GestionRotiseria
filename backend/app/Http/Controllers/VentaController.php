<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use Illuminate\Http\Request;

class VentaController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/ventas",
     *     summary="Obtener lista de ventas",
     *     description="Requiere autenticación con token JWT.",
     *     tags={"Ventas"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de ventas obtenida correctamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se pudieron obtener las ventas"
     *     )
     * )
     */
    public function index()
    {
        try {
            $ventas = Venta::all();
            return response()->json($ventas, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'No se pudieron obtener las ventas',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/ventas",
     *     summary="Registrar una nueva venta",
     *     description="Crea una venta nueva. Requiere autenticación con token JWT.",
     *     tags={"Ventas"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"fecha", "monto_venta", "id_pedido"},
     *             @OA\Property(property="fecha", type="string", format="date", example="2025-10-06"),
     *             @OA\Property(property="monto_venta", type="number", format="float", example=1500.75),
     *             @OA\Property(property="id_pedido", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Venta registrada correctamente"
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
                'fecha' => 'required|date',
                'monto_venta' => 'required|numeric|min:0',
                'id_pedido' => 'required|integer|exists:pedido,id_pedido',
            ]);

            $venta = Venta::create($request->all());

            return response()->json([
                'message' => 'Venta registrada correctamente',
                'data' => $venta
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al registrar la venta',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/ventas/{id}",
     *     summary="Obtener una venta por ID",
     *     description="Requiere autenticación con token JWT.",
     *     tags={"Ventas"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la venta",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Venta obtenida correctamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Venta no encontrada"
     *     )
     * )
     */
    public function show($id)
    {
        $venta = Venta::find($id);

        if (!$venta) {
            return response()->json([
                'message' => 'Venta no encontrada',
            ], 404);
        }

        return response()->json($venta, 200);
    }

    /**
     * @OA\Put(
     *     path="/api/ventas/{id}",
     *     summary="Actualizar una venta existente",
     *     description="Actualiza una venta por ID. Requiere autenticación con token JWT.",
     *     tags={"Ventas"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la venta a actualizar",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"fecha", "monto_venta", "id_pedido"},
     *             @OA\Property(property="fecha", type="string", format="date", example="2025-10-06"),
     *             @OA\Property(property="monto_venta", type="number", format="float", example=1800.00),
     *             @OA\Property(property="id_pedido", type="integer", example=2)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Venta actualizada correctamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Venta no encontrada"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error al actualizar la venta"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $venta = Venta::find($id);

        if (!$venta) {
            return response()->json([
                'message' => 'Venta no encontrada'
            ], 404);
        }

        try {
            $request->validate([
                'fecha' => 'required|date',
                'monto_venta' => 'required|numeric|min:0',
                'id_pedido' => 'required|integer|exists:pedido,id_pedido',
            ]);

            $venta->update($request->only([
                'fecha',
                'monto_venta',
                'id_pedido'
            ]));

            return response()->json([
                'message' => 'Venta actualizada correctamente',
                'data' => $venta
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar la venta',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/ventas/{id}",
     *     summary="Eliminar una venta",
     *     description="Elimina una venta por ID. Requiere autenticación con token JWT.",
     *     tags={"Ventas"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la venta a eliminar",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Venta eliminada correctamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Venta no encontrada"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error al eliminar la venta"
     *     )
     * )
     */
    public function destroy($id)
    {
        $venta = Venta::find($id);

        if (!$venta) {
            return response()->json([
                'message' => 'Venta no encontrada'
            ], 404);
        }

        try {
            $venta->delete();

            return response()->json([
                'message' => 'Venta eliminada correctamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al eliminar la venta',
                'error' => $e->getMessage()
            ], 400);
        }
    }
}
