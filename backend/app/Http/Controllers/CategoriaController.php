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
        return response()->json(Categoria::select('id_categoria', 'nombre_categoria')->get(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre_categoria' => 'required|string|max:255|unique:Categoria,nombre_categoria',
        ]);

        $categoria = Categoria::create([
            'nombre_categoria' => $request->nombre_categoria,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Categoria $categoria)
    {
        return response()->json($categoria, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Categoria $categoria)
    {
        $request->validate([
            'nombre_categoria' => 'required|string|max:255|unique:Categoria,nombre_categoria,' . $categoria->id_categoria . ',id_categoria',
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
