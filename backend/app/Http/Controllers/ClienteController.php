<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/clientes",
     *     summary="Obtener lista de clientes",
     *     tags={"Clientes"},
     *      security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de clientes obtenida correctamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se pudieron obtener los clientes"
     *     )
     * )
     */
    public function index()
    {
        try {
            $clientes = Cliente::all();
            return response()->json($clientes, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'No se pudieron obtener los clientes',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/clientes",
     *     summary="Crear un nuevo cliente",
     *     description="Crea un cliente nuevo. Requiere autenticación con token JWT.",
     *     tags={"Clientes"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"dni_cliente", "nombre_cliente", "telefono_cliente", "direccion_cliente"},
     *             @OA\Property(property="dni_cliente", type="string", example="12345678"),
     *             @OA\Property(property="nombre_cliente", type="string", example="Juan Pérez"),
     *             @OA\Property(property="telefono_cliente", type="string", example="0345-4212345"),
     *             @OA\Property(property="direccion_cliente", type="string", example="Av. San Martín 123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Cliente creado correctamente"
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
                'dni_cliente' => [
                    'required',
                    'digits:8',        
                    'numeric',         
                    'unique:cliente,dni_cliente'
                ],
                'nombre_cliente' => 'required|string|max:100',
                'telefono_cliente' => 'required|string|max:20',
                'direccion_cliente' => 'required|string|max:255',
            ]);

            $cliente = Cliente::create($request->all());

            return response()->json([
                'message' => 'Cliente creado correctamente',
                'cliente' => $cliente
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al crear el cliente',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/clientes/{dni}",
     *     summary="Obtener un cliente por DNI",
     *     tags={"Clientes"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="dni",
     *         in="path",
     *         required=true,
     *         description="DNI del cliente",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cliente obtenido correctamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Cliente no encontrado"
     *     )
     * )
     */
    public function show($dni)
    {
        $cliente = Cliente::find($dni);

        if (!$cliente) {
            return response()->json([
                'message' => 'Cliente no encontrado',
            ], 404);
        }

        return response()->json([
                'message' => 'Cliente obtenido correctamente',
                'cliente' => $cliente
            ], 200);
    }

    /**
     * @OA\Put(
     *     path="/api/clientes/{dni}",
     *     summary="Actualizar un cliente existente",
     *     description="Actualiza un cliente por DNI. Requiere autenticación con token JWT.",
     *     tags={"Clientes"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="dni",
     *         in="path",
     *         required=true,
     *         description="DNI del cliente a actualizar",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nombre_cliente", "telefono_cliente", "direccion_cliente"},
     *             @OA\Property(property="nombre_cliente", type="string", example="Ana Gómez"),
     *             @OA\Property(property="telefono_cliente", type="string", example="0345-4212345"),
     *             @OA\Property(property="direccion_cliente", type="string", example="Calle Falsa 123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cliente actualizado correctamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Cliente no encontrado"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error al actualizar el cliente"
     *     )
     * )
     */
    public function update(Request $request, $dni)
    {
        $cliente = Cliente::find($dni);

        if (!$cliente) {
            return response()->json([
                'message' => 'Cliente no encontrado'
            ], 404);
        }

        try {
            $request->validate([
                'nombre_cliente' => 'required|string|max:100',
                'telefono_cliente' => 'required|string|max:20',
                'direccion_cliente' => 'required|string|max:255',
            ]);

            $cliente->update($request->only([
                'nombre_cliente',
                'telefono_cliente',
                'direccion_cliente'
            ]));

            return response()->json([
                'message' => 'Cliente actualizado correctamente',
                'cliente' => $cliente
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar el cliente',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/clientes/{dni}",
     *     summary="Eliminar un cliente",
     *     description="Elimina un cliente por DNI. Requiere autenticación con token JWT.",
     *     tags={"Clientes"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="dni",
     *         in="path",
     *         required=true,
     *         description="DNI del cliente a eliminar",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cliente eliminado correctamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Cliente no encontrado"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error al eliminar el cliente"
     *     )
     * )
     */
    public function destroy($dni)
    {
        $cliente = Cliente::find($dni);

        if (!$cliente) {
            return response()->json([
                'message' => 'Cliente no encontrado'
            ], 404);
        }

        try {
            $cliente->delete();

            return response()->json([
                'message' => 'Cliente eliminado correctamente',
                'dni' => $dni
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al eliminar el cliente',
                'error' => $e->getMessage()
            ], 400);
        }
    }
}
