<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class PerfilController extends Controller
{
    public function index(Request $request)
    {
        $usuario = Auth::user();
        $layout = $usuario->email === 'torresgiraldojhon1224@outlook.es' ? 'layouts.admin' : 'layouts.panel';

        if ($request->ajax()) {
            return view('usuarios._content', compact('usuario'));
        }

        return view('usuarios.perfil', compact('usuario', 'layout'));
    }

    /**
     * Actualiza la contraseña del usuario.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'current_password.current_password' => 'La contraseña actual no es correcta.',
            'password.confirmed'                => 'La confirmación de la nueva contraseña no coincide.',
            'password.min'                      => 'La nueva contraseña debe tener al menos 8 caracteres.',
        ]);

        $user = Auth::user();
        $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
        $user->save();

        return back()->with('success', '✅ Contraseña actualizada correctamente.');
    }
}
