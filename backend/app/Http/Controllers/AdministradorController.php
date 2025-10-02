<?php

namespace App\Http\Controllers;

use App\Models\Administrador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdministradorController extends Controller
{
    public function index()
    {
       return Administrador::all();
    }

    public function show()
    {
        $admin = Administrador::first();
        return response()->json($admin, 200);
    }

 
    public function update(Request $request)
    {
        $admin = Administrador::first();

        $request->validate([
            'email_administrador' => 'sometimes|required|email|unique:administrador,email_administrador,' . $admin->id_administrador . ',id_administrador',
            'contrasena_administrador' => 'sometimes|required|string|min:6'
        ]);

        if ($request->has('contrasena_administrador')) {
            $request['contrasena_administrador'] = Hash::make($request->contrasena_administrador);
        }

        $admin->update($request->all());

        return response()->json([
            'message' => 'Administrador actualizado correctamente',
            'data' => $admin
        ], 200);
    }

   
 public function destroy()
    {
        $admin = Administrador::first();
        $admin->delete();

        return response()->json([
            'message' => 'Administrador eliminado correctamente'
        ], 200);
    }
}

