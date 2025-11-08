<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

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
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"nombre_producto", "precio_producto", "id_categoria"},
     *                 @OA\Property(property="nombre_producto", type="string", maxLength=100, example="Coca Cola"),
     *                 @OA\Property(property="descripcion_producto", type="string", maxLength=255, example="Bebida gaseosa sabor cola"),
     *                 @OA\Property(property="precio_producto", type="number", format="float", example=150.50),
     *                 @OA\Property(property="disponible", type="integer", enum={0, 1}, example=1, description="1=disponible, 0=no disponible"),
     *                 @OA\Property(property="id_categoria", type="integer", example=1),
     *                 @OA\Property(property="imagen", type="string", format="binary", description="Imagen del producto (jpg, jpeg, png, webp, gif)")
     *             )
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
            // Validación
            $validated = $request->validate([
                'nombre_producto' => 'required|string|max:100',
                'descripcion_producto' => 'nullable|string|max:255',
                'precio_producto' => 'required|numeric|min:0',
                'disponible' => 'nullable',
                'id_categoria' => 'required|integer|exists:categoria,id_categoria',
                'imagen' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:5120',
            ]);

            // Preparar datos
            $data = [
                'nombre_producto' => $validated['nombre_producto'],
                'descripcion_producto' => $validated['descripcion_producto'] ?? '',
                'precio_producto' => $validated['precio_producto'],
                'disponible' => $request->input('disponible', 1) == 1 ? 1 : 0,
                'id_categoria' => $validated['id_categoria'],
            ];

            // Guardar imagen si existe
            if ($request->hasFile('imagen')) {
                $path = $request->file('imagen')->store('productos', 'public');
                $data['imagen'] = $path;
            }

            // Crear producto
            $producto = Producto::create($data);

            return response()->json([
                'message' => 'Producto creado correctamente',
                'producto' => $producto
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
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
        try {
            $producto = Producto::with('categoria')->find($id);

            if (!$producto) {
                return response()->json(['message' => 'Producto no encontrado'], 404);
            }

            return response()->json([
                'message' => 'Producto obtenido correctamente',
                'producto' => $producto
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener el producto',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/productos/{id}",
     *     summary="Actualizar un producto existente",
     *     description="Actualiza un producto por ID usando POST con _method=PUT. Requiere autenticación con token JWT.",
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
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="nombre_producto", type="string", maxLength=100, example="Pepsi"),
     *                 @OA\Property(property="descripcion_producto", type="string", maxLength=255, example="Bebida gaseosa sabor cola"),
     *                 @OA\Property(property="precio_producto", type="number", format="float", example=140.00),
     *                 @OA\Property(property="disponible", type="integer", enum={0, 1}, example=0, description="1=disponible, 0=no disponible"),
     *                 @OA\Property(property="id_categoria", type="integer", example=2),
     *                 @OA\Property(property="imagen", type="string", format="binary", description="Imagen del producto (jpg, jpeg, png, webp, gif)"),
     *                 @OA\Property(property="_method", type="string", example="PUT", description="Método HTTP para actualización")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200, description="Producto actualizado correctamente"),
     *     @OA\Response(response=404, description="Producto no encontrado"),
     *     @OA\Response(response=422, description="Error de validación"),
     *     @OA\Response(response=500, description="Error interno")
     * )
     */
    public function update(Request $request, $id)
    {
        try {
            $producto = Producto::find($id);

            if (!$producto) {
                return response()->json(['message' => 'Producto no encontrado'], 404);
            }

            // Validación
            $validated = $request->validate([
                'nombre_producto' => 'nullable|string|max:100',
                'descripcion_producto' => 'nullable|string|max:255',
                'precio_producto' => 'nullable|numeric|min:0',
                'disponible' => 'nullable',
                'id_categoria' => 'nullable|integer|exists:categoria,id_categoria',
                'imagen' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:5120',
            ]);

            // Actualizar solo los campos enviados
            if ($request->has('nombre_producto')) {
                $producto->nombre_producto = $validated['nombre_producto'];
            }
            if ($request->has('descripcion_producto')) {
                $producto->descripcion_producto = $validated['descripcion_producto'] ?? '';
            }
            if ($request->has('precio_producto')) {
                $producto->precio_producto = $validated['precio_producto'];
            }
            if ($request->has('disponible')) {
                $producto->disponible = $request->input('disponible') == 1 ? 1 : 0;
            }
            if ($request->has('id_categoria')) {
                $producto->id_categoria = $validated['id_categoria'];
            }

            // Actualizar imagen si se envía una nueva
            if ($request->hasFile('imagen')) {
                // Eliminar imagen anterior
                if ($producto->imagen) {
                    Storage::disk('public')->delete($producto->imagen);
                }
                // Guardar nueva imagen
                $path = $request->file('imagen')->store('productos', 'public');
                $producto->imagen = $path;
            }

            $producto->save();

            return response()->json([
                'message' => 'Producto actualizado correctamente',
                'producto' => $producto->load('categoria')
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
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
        try {
            $producto = Producto::find($id);

            if (!$producto) {
                return response()->json(['message' => 'Producto no encontrado'], 404);
            }

            // Eliminar imagen del storage
            if ($producto->imagen) {
                Storage::disk('public')->delete($producto->imagen);
            }

            $producto->delete();

            return response()->json(['message' => 'Producto eliminado correctamente'], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al eliminar el producto',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}