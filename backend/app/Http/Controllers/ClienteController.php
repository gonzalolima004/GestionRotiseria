<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    /**
     * Listar todos los clientes
     */
    public function index()
    {
        $clientes = Cliente::with('pedidos')->get();
        return response()->json($clientes);
    }

    /**
     * Crear un nuevo cliente
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'dni_cliente' => 'required|string|max:20|unique:cliente,dni_cliente',
                'nombre_cliente' => 'required|string|max:50',
                'telefono_cliente' => 'nullable|string|max:50',
                'direccion_cliente' => 'nullable|string|max:50'
            ]);

            $cliente = Cliente::create([
                'dni_cliente' => $request->dni_cliente,
                'nombre_cliente' => $request->nombre_cliente,
                'telefono_cliente' => $request->telefono_cliente ?? '', 
                'direccion_cliente' => $request->direccion_cliente ?? ''
            ]);

            return response()->json($cliente, 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al crear el cliente',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Mostrar un cliente específico
     */
    public function show(Cliente $cliente)
    {
        $cliente->load('pedidos');
        return response()->json($cliente);
    }

    /**
     * Actualizar un cliente
     */
    public function update(Request $request, Cliente $cliente)
    {
        $request->validate([
            'nombre_cliente' => 'sometimes|required|string|max:50',
            'telefono_cliente' => 'sometimes|nullable|string|max:50',
            'direccion_cliente' => 'sometimes|nullable|string|max:50'
        ]);

        $cliente->update($request->all());

        return response()->json([
            'message' => 'Cliente actualizado correctamente',
            'data' => $cliente
        ], 200);
    }

    /**
     * Eliminar un cliente
     */
    public function destroy(Cliente $cliente)
    {
        $cliente->delete();

        return response()->json([
            'message' => 'Cliente eliminado correctamente'
        ], 200);
    }
}
