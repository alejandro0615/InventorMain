<?php

namespace App\Http\Controllers;

use App\Models\Local;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * Controlador para gestionar la autenticación y el registro de nuevos locales/usuarios.
 */
class LoginController extends Controller
{
    /**
     * Muestra la vista de registro.
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Procesa el registro de un nuevo negocio (Local) y su administrador (Usuario).
     */
    public function register(Request $request)
    {
        $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'email'        => ['required', 'email', 'max:255', 'unique:users,email'],
            'razon_social' => ['required', 'string', 'max:255'],
            'nit'          => ['required', 'string', 'min:9', 'max:12', 'unique:users,nit'],
            'rut'          => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
            'telefono'     => ['required', 'string', 'min:10', 'max:15'],
            'nombre_local' => ['required', 'string', 'max:255'],
            'password'     => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Ejecutar en una transacción para asegurar consistencia
        DB::transaction(function () use ($request) {
            // 1. Crear el local
            $local = Local::create([
                'nombre' => $request->nombre_local,
            ]);

            // 2. Manejar la carga del archivo RUT
            $rutPath = null;
            if ($request->hasFile('rut')) {
                $rutPath = $request->file('rut')->store('rut/' . time(), 'public');
            }

            // 3. Crear el usuario administrador asociado al local
            User::create([
                'name'         => $request->name,
                'email'        => $request->email,
                'razon_social' => $request->razon_social,
                'nit'          => $request->nit,
                'rut'          => $rutPath,
                'telefono'     => $request->telefono,
                'local_id'     => $local->id,
                'estado'       => 'activo',
                'password'     => Hash::make($request->password),
            ]);
        });

        return redirect()->route('login')
            ->with('status', '✅ Empresa y local creados. ¡Inicia sesión!');
    }

    /**
     * Gestiona el inicio de sesión del usuario.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('principal');
        }

        return back()->withErrors([
            'email' => 'Las credenciales no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    /**
     * Cierra la sesión activa.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
