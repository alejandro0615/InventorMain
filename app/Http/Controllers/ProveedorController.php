<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controlador para gestionar el directorio de Clientes/Proveedores.
 */
class ProveedorController extends Controller
{
    /**
     * Lista los proveedores asociados al local actual.
     */
    public function index(Request $request)
    {
        $localId = Auth::user()->local_id;
        $search = $request->input('q');

        $proveedores = Proveedor::where('local_id', $localId)
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nombre', 'like', "%{$search}%")
                      ->orWhere('nit', 'like', "%{$search}%")
                      ->orWhere('razon_social', 'like', "%{$search}%");
                });
            })
            ->get();

        if ($request->ajax()) {
            return view('proveedores._content', compact('proveedores', 'search'));
        }

        return view('proveedores.index', compact('proveedores', 'search'));
    }

    /**
     * Muestra el formulario para registrar un nuevo proveedor.
     */
    public function create()
    {
        return view('proveedores.create');
    }

    /**
     * Almacena un nuevo proveedor en la base de datos.
     */
    public function store(Request $request)
    {
        $localId = Auth::user()->local_id;

        $data = $request->validate([
            'nombre'       => ['required', 'string', 'max:255'],
            'razon_social' => ['nullable', 'string', 'max:255'],
            'direccion'    => ['required', 'string', 'max:255'],
            'telefono'     => ['required', 'string', 'max:20'],
            'email'        => [
                'required', 'email', 'max:255', 
                'unique:proveedores,email,NULL,id,local_id,' . $localId
            ],
            'nit'          => [
                'nullable', 'string', 'max:50', 
                'unique:proveedores,nit,NULL,id,local_id,' . $localId
            ],
        ]);

        $data['local_id'] = $localId;
        Proveedor::create($data);

        return redirect()
            ->route('proveedores.index')
            ->with('success', 'Proveedor creado exitosamente.');
    }

    /**
     * Muestra la información de un proveedor.
     */
    public function show(Proveedor $proveedor)
    {
        $this->authorizeOwner($proveedor);

        return view('proveedores.show', compact('proveedor'));
    }

    /**
     * Muestra el formulario para editar un proveedor.
     */
    public function edit(Proveedor $proveedor)
    {
        $this->authorizeOwner($proveedor);

        return view('proveedores.edit', compact('proveedor'));
    }

    /**
     * Actualiza la información del proveedor.
     */
    public function update(Request $request, Proveedor $proveedor)
    {
        $this->authorizeOwner($proveedor);
        $localId = Auth::user()->local_id;

        $data = $request->validate([
            'nombre'       => ['required', 'string', 'max:255'],
            'razon_social' => ['nullable', 'string', 'max:255'],
            'direccion'    => ['required', 'string', 'max:255'],
            'telefono'     => ['required', 'string', 'max:20'],
            'email'        => [
                'required', 'email', 'max:255', 
                'unique:proveedores,email,' . $proveedor->id . ',id,local_id,' . $localId
            ],
            'nit'          => [
                'required', 'string', 'max:20', 
                'unique:proveedores,nit,' . $proveedor->id . ',id,local_id,' . $localId
            ],
        ]);

        $proveedor->update($data);

        return redirect()
            ->route('proveedores.index')
            ->with('success', 'Proveedor actualizado exitosamente.');
    }

    /**
     * Elimina un proveedor.
     */
    public function destroy(Proveedor $proveedor)
    {
        $this->authorizeOwner($proveedor);
        $proveedor->delete();

        return redirect()
            ->route('proveedores.index')
            ->with('success', 'Proveedor eliminado exitosamente.');
    }

    /**
     * Verifica si el proveedor pertenece al local del usuario autenticado.
     */
    protected function authorizeOwner(Proveedor $proveedor)
    {
        if ($proveedor->local_id !== Auth::user()->local_id) {
            abort(403, 'No tienes permiso para acceder a este recurso.');
        }
    }
}
