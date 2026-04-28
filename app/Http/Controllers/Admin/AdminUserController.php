<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Local;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminUserController extends Controller
{
    /**
     * Muestra la lista de usuarios.
     */
    public function index()
    {
        $users = User::with('negocio')->where('id', '!=', auth()->id())->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Registra un nuevo usuario y su local.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'email'        => ['required', 'email', 'max:255', 'unique:users,email'],
            'razon_social' => ['required', 'string', 'max:255'],
            'nit'          => ['required', 'string', 'min:9', 'max:12', 'unique:users,nit'],
            'telefono'     => ['required', 'string', 'min:10', 'max:15'],
            'nombre_local' => ['required', 'string', 'max:255'],
            'password'     => ['required', 'string', 'min:8'],
        ]);

        DB::transaction(function () use ($request) {
            $local = Local::create([
                'nombre' => $request->nombre_local,
            ]);

            User::create([
                'name'         => $request->name,
                'email'        => $request->email,
                'razon_social' => $request->razon_social,
                'nit'          => $request->nit,
                'telefono'     => $request->telefono,
                'local_id'     => $local->id,
                'estado'       => 'activo',
                'role'         => 'user',
                'password'     => Hash::make($request->password),
            ]);
        });

        return redirect()->back()->with('status', '✅ Usuario registrado correctamente.');
    }

    /**
     * Alterna el estado del usuario (activo/inactivo).
     */
    public function toggleStatus(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'No puedes bloquearte a ti mismo.');
        }

        $user->estado = $user->estado === 'activo' ? 'inactivo' : 'activo';
        $user->save();

        $mensaje = $user->estado === 'activo' ? 'Usuario activado.' : 'Usuario bloqueado.';
        return redirect()->back()->with('status', "✅ $mensaje");
    }

    /**
     * Elimina un usuario.
     */
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'No puedes eliminarte a ti mismo.');
        }

        $user->delete();
        return redirect()->back()->with('status', '✅ Usuario eliminado correctamente.');
    }
}
