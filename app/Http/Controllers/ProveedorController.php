<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProveedorController extends Controller
{

    public function index(Request $request)
    {
        $localId = Auth::user()->local_id;

        $proveedores = Proveedor::where('local_id', $localId)->get();

        if ($request->ajax()) {
        return view('proveedores._content', compact('proveedores'));
    }

        return view('proveedores.index', compact('proveedores'));

    }

    public function create()
    {
        return view('proveedores.create');
    }

    public function store(Request $request)
    {
        $Data = $request->validate([
            'nombre' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
            'email' => 'required|email|max:255|unique:proveedores,email',
            'nit' => 'nullable|string|max:50|unique:proveedores,nit',
        ]);
        $Data['local_id'] = Auth::user()->local_id;

        Proveedor::create($Data);

        return redirect()->route('proveedores.index')
            ->with('success', 'Proveedor creado exitosamente.');
    }

    public function show(Proveedor $proveedor)
    {
        return view('proveedores.show', compact('proveedor'));
    }

    public function edit(Proveedor $proveedor)
    {
        return view('proveedores.edit', compact('proveedor'));
    }

    public function update(Request $request, Proveedor $proveedor)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
            'email' => 'required|email|max:255|unique:proveedores,email,' . $proveedor->id,
            'nit' => 'required|string|max:20|unique:proveedores,nit,' . $proveedor->id,
        ]);
        $proveedor->update($data);

        return redirect()->route('proveedores.index')
            ->with('success', 'Proveedor actualizado exitosamente.');
    }


    public function destroy(Proveedor $proveedor)
    {
        $proveedor->delete();

        return redirect()->route('proveedores.index')
            ->with('success', 'Proveedor eliminado exitosamente.');
    }
}
