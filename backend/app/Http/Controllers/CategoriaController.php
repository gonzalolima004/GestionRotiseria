<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    /**
     * @OA\Get(
     * path="/api/categorias",
     * summary="Obtener lista de categorias",
     * tags={"Categorias"},
     * @OA\Response(
     * response=200,
     * description="Lista de categorias obtenida correctamente"
     * ),
     * @OA\Response(
     * response=404,
     * description="No se pudieron obtener las categorías"
     * )
     * )
     */
    public function index()
    {
        try {
            $categorias = Categoria::all();
            return response()->json($categorias, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'No se pudieron obtener las categorías',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * @OA\Post(
     * path="/api/categorias",
     * summary="Crear una nueva categoría",
     * description="Crea una categoría nueva. Requiere autenticación con token JWT.",
     * tags={"Categorias"},
     * security={{"bearerAuth":{}}},
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * required={"nombre_categoria"},
     * @OA\Property(property="nombre_categoria", type="string", maxLength=50, example="Bebidas")
     * )
     * ),
     * @OA\Response(
     * response=201,
     * description="Categoría creada correctamente"
     * ),
     * @OA\Response(
     * response=401,
     * description="No autorizado - Token ausente o inválido",
     * @OA\JsonContent(
     * @OA\Property(property="error", type="string", example="No autenticado o token inválido")
     * )
     * ),
     * @OA\Response(
     * response=422,
     * description="Datos inválidos"
     * ),
     * @OA\Response(
     * response=500,
     * description="Error interno"
     * )
     * )
     */

    public function store(Request $request)
    {
        try {
            $request->validate([
                'nombre_categoria' => 'required|string|max:50|unique:categoria,nombre_categoria',
            ]);

            $categoria = Categoria::create([
                'nombre_categoria' => $request->nombre_categoria,
            ]);

            return response()->json([
                'message' => 'Categoria creada correctamente',
                'data' => $categoria
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al crear la categoria',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * @OA\Get(
     * path="/api/categorias/{id}",
     * summary="Obtener una categoria por ID",
     * tags={"Categorias"},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID de la categoria",
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     * response=200,
     * description="Categoria obtenida correctamente"
     * ),
     * @OA\Response(
     * response=404,
     * description="Categoria no encontrada"
     * )
     * )
     */
    public function show($id)
    {
        $categoria = Categoria::find($id);

        if (!$categoria) {
            return response()->json([
                'message' => 'Categoria no encontrada',
            ], 404);
        }

        return response()->json($categoria, 200);
    }

    /**
     * @OA\Put(
     *     path="/api/categorias/{id}",
     *     summary="Actualizar una categoría existente",
     *     description="Actualiza una categoría por ID. Requiere autenticación con token JWT.",
     *     tags={"Categorias"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la categoría a actualizar",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nombre_categoria"},
     *             @OA\Property(property="nombre_categoria", type="string", maxLength=255, example="Snacks")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Categoría actualizada correctamente"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado - Token ausente o inválido",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="No autenticado o token inválido")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error al actualizar la categoría"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno"
     *     )
     * )
     */

    public function update(Request $request, Categoria $categoria)
    {
        try {
            $request->validate([
                'nombre_categoria' => 'required|string|max:255|unique:categoria,nombre_categoria,' . $categoria->id_categoria . ',id_categoria',
            ]);

            $categoria->update([
                'nombre_categoria' => $request->nombre_categoria,
            ]);

            return response()->json([
                'message' => 'Categoria actualizada correctamente',
                'data' => $categoria
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar la categoria',
                'error' => $e->getMessage()
            ], 400);
        }
    }


    /**
     * @OA\Delete(
     *     path="/api/categorias/{id}",
     *     summary="Eliminar una categoría",
     *     description="Elimina una categoría por ID. Requiere autenticación con token JWT.",
     *     tags={"Categorias"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la categoría a eliminar",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Categoría eliminada correctamente"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado - Token ausente o inválido",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="No autenticado o token inválido")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Categoría no encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="La categoría con el ID especificado no existe")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error al eliminar la categoría"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno"
     *     )
     * )
     */
    public function destroy($id)
{
    $categoria = Categoria::find($id);

    if (!$categoria) {
        return response()->json([
            'message' => 'Categoria no encontrada'
        ], 404);
    }

    try {
        $categoria->delete();

        return response()->json([
            'message' => 'Categoria eliminada correctamente'
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Error al eliminar la categoria',
            'error' => $e->getMessage()
        ], 400);
    }
}


}
