<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       return Categoria::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre_categoria' => 'required|string|max:255|unique:categoria,nombre_categoria',
        ]);

        $categoria = Categoria::create([
            'nombre_categoria' => $request->nombre_categoria,
        ]);

        return response()->json([
            'message' => 'Categoría creada correctamente',
            'data' => $categoria
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Categoria $categoria){
        try {
            return response()->json($categoria, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Categoria no encontrada',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Categoria $categoria)
    {
        $request->validate([
            'nombre_categoria' => 'required|string|max:255|unique:categoria,nombre_categoria,' . $categoria->id_categoria . ',id_categoria',
        ]);

        $categoria->update([
            'nombre_categoria' => $request->nombre_categoria,
        ]);

        return response()->json([
            'message' => 'Categoría actualizada correctamente',
            'data' => $categoria
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Categoria $categoria)
    {
        $categoria->delete();

        return response()->json([
            'message' => 'Categoría eliminada correctamente'
        ], 200);
    }
}
