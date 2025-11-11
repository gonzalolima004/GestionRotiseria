<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class PasswordResetController extends Controller
{
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'No existe un usuario con ese correo'], 404);
        }

        $token = Str::random(64);
        Cache::put('reset_' . $user->email, $token, now()->addHour());

        $link = "http://localhost:4200/reset-password?token={$token}&email={$user->email}";

        Mail::send('emails.password_reset', ['user' => $user, 'link' => $link], function($message) use ($user) {
            $message->to($user->email)->subject('Recuperación de contraseña');
        });

        return response()->json(['message' => 'Correo de recuperación enviado']);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required',
            'password' => 'required|min:6|confirmed'
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        $storedToken = Cache::get('reset_' . $user->email);
        if (!$storedToken || $storedToken !== $request->token) {
            return response()->json(['message' => 'El enlace ha expirado o no es válido'], 400);
        }

        // Actualizar contraseña
        $user->password = Hash::make($request->password);
        $user->save();

        // Borrar el token del cache
        Cache::forget('reset_' . $user->email);

        return response()->json(['message' => 'Contraseña restablecida correctamente']);
    }
}
