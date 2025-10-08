<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/productos",
     *     summary="Obtener lista de productos",
     *     tags={"Productos"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de productos obtenida correctamente"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al obtener los productos"
     *     )
     * )
     */
    public function index()
    {
        try {
            $productos = Producto::with('categoria')->get();
            return response()->json($productos, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener los productos',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/productos",
     *     summary="Crear un nuevo producto",
     *     description="Crea un producto nuevo. Requiere autenticación con token JWT.",
     *     tags={"Productos"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nombre_producto", "precio_producto", "id_categoria"},
     *             @OA\Property(property="nombre_producto", type="string", maxLength=100, example="Coca Cola"),
     *             @OA\Property(property="descripcion_producto", type="string", example="Bebida gaseosa sabor cola"),
     *             @OA\Property(property="precio_producto", type="number", format="float", example=150.50),
     *             @OA\Property(property="disponible", type="boolean", example=true),
     *             @OA\Property(property="id_categoria", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(response=201, description="Producto creado correctamente"),
     *     @OA\Response(response=422, description="Datos inválidos"),
     *     @OA\Response(response=500, description="Error interno")
     * )
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'nombre_producto' => 'required|string|max:100|unique:producto,nombre_producto',
                'descripcion_producto' => 'nullable|string',
                'precio_producto' => 'required|numeric|min:0',
                'disponible' => 'boolean',
                'id_categoria' => 'required|integer|exists:categoria,id_categoria',
            ]);

            $producto = Producto::create($request->all());

            return response()->json([
                'message' => 'Producto creado correctamente',
                'producto' => $producto
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al crear el producto',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/productos/{id}",
     *     summary="Obtener un producto por ID",
     *     tags={"Productos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del producto",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Producto obtenido correctamente"),
     *     @OA\Response(response=404, description="Producto no encontrado")
     * )
     */
    public function show($id)
    {
        $producto = Producto::with('categoria')->find($id);

        if (!$producto) {
            return response()->json(['message' => 'Producto no encontrado'], 404);
        }

        return response()->json([
                'message' => 'Producto obtenido correctamente',
                'producto' => $producto
            ], 200);
    }

    /**
     * @OA\Put(
     *     path="/api/productos/{id}",
     *     summary="Actualizar un producto existente",
     *     description="Actualiza un producto por ID. Requiere autenticación con token JWT.",
     *     tags={"Productos"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del producto a actualizar",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nombre_producto", type="string", maxLength=100, example="Pepsi"),
     *             @OA\Property(property="descripcion_producto", type="string", example="Bebida gaseosa sabor cola"),
     *             @OA\Property(property="precio_producto", type="number", format="float", example=140.00),
     *             @OA\Property(property="disponible", type="boolean", example=false),
     *             @OA\Property(property="id_categoria", type="integer", example=2)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Producto actualizado correctamente"),
     *     @OA\Response(response=404, description="Producto no encontrado"),
     *     @OA\Response(response=500, description="Error interno")
     * )
     */
    public function update(Request $request, Producto $producto)
    {
        try {
            $request->validate([
                'nombre_producto' => 'string|max:100|unique:producto,nombre_producto,' . $producto->id_producto . ',id_producto',
                'descripcion_producto' => 'nullable|string',
                'precio_producto' => 'numeric|min:0',
                'disponible' => 'boolean',
                'id_categoria' => 'integer|exists:categoria,id_categoria',
            ]);

            $producto->update($request->all());

            return response()->json([
                'message' => 'Producto actualizado correctamente',
                'producto' => $producto
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar el producto',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/productos/{id}",
     *     summary="Eliminar un producto",
     *     description="Elimina un producto por ID. Requiere autenticación con token JWT.",
     *     tags={"Productos"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del producto a eliminar",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Producto eliminado correctamente"),
     *     @OA\Response(response=404, description="Producto no encontrado"),
     *     @OA\Response(response=500, description="Error interno")
     * )
     */
    public function destroy($id)
{
    $producto = Producto::find($id);

    if (!$producto) {
        return response()->json([
            'message' => 'Producto no encontrado'
        ], 404);
    }

    try {
        $producto->delete();

        return response()->json([
            'message' => 'Producto eliminado correctamente',
            'producto' => $id
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Error al eliminar el producto',
            'error' => $e->getMessage()
        ], 500);
    }
}

}
