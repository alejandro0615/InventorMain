<?php

namespace App\Http\Controllers;

use App\Models\Local;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

     public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'razon_social' => ['required', 'string', 'max:255'],
            'nit' => ['required', 'string', 'min:9', 'max:12', 'unique:users,nit'],
            'rut' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
            'telefono' => ['required', 'string', 'min:10', 'max:15'],
            'nombre_local' => ['required', 'string', 'max:255'], // CAMPO DEL FORMULARIO
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // TRANSACCIÓN para crear local + usuario
        DB::transaction(function () use ($request) {
            // 1. Crear LOCAL primero
            $local_id = DB::table('locales')->insertGetId([
                'nombre' => $request->nombre_local,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 2. Manejar archivo RUT
            $rutPath = null;
            if ($request->hasFile('rut')) {
                $rutPath = $request->file('rut')->store('rut/' . time(), 'public');
            }

            // 3. Crear USUARIO con todos los campos
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'razon_social' => $request->razon_social,
                'nit' => $request->nit,
                'rut' => $rutPath,
                'telefono' => $request->telefono,
                'local_id' => $local_id,
                'estado' => 'activo',
                'password' => Hash::make($request->password),
            ]);
        });

        return redirect()->route('login')
            ->with('status', '✅ Empresa y local creados. ¡Inicia sesión!');
    }

    public function login(Request $request)
    {
        // dd($request->all());
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('principal');
        }

        return back()->withErrors([
            'email' => 'las credenciales no coinciden con nuestros registros.',
            'password' => 'las credenciales no coinciden con nuestros registros.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect(route('login'));
    }




}
