<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Producto::all();
    }

    /**
     * Store a newly created resource in storage.
     */
public function store(Request $request)
{
    try {
        $request->validate([
            'nombre_producto' => 'required|string|max:255',
            'descripcion_producto' => 'nullable|string',
            'precio_producto' => 'required|numeric',
            'disponible' => 'required|boolean',
            'id_categoria' => 'required|exists:categoria,id_categoria'
        ]);

        $producto = Producto::create($request->all());
        return response()->json($producto, 201);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Error al crear el producto',
            'error' => $e->getMessage()
        ], 400);
    }
}


    /**
     * Display the specified resource.
     */
    public function show(Producto $producto){
        try {
            return response()->json($producto, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Producto no encontrado',
                'error' => $e->getMessage()
            ], 404);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Producto $producto)
    {
        $request->validate([
            'nombre_producto' => 'sometimes|required|string|max:255',
            'descripcion_producto' => 'sometimes|nullable|string',
            'precio_producto' => 'sometimes|required|numeric',
            'disponible' => 'sometimes|required|boolean',
            'id_categoria' => 'sometimes|required|exists:categoria,id_categoria'
        ]);

        $producto->update($request->all());
        
        return response()->json([
            'message' => 'Producto actualizado correctamente',
            'data' => $producto
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Producto $producto)
    {
        $producto->delete();

        return response()->json([
            'message' => 'Producto eliminado correctamente'
        ], 200);
    }
}
