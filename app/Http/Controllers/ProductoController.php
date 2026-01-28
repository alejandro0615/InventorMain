<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $localId = Auth::user()->local_id;
        $productos = Producto::where('local_id', $localId)->get();

        if ($request->ajax()) {
            return view('productos._content', compact('productos'));
        }

        return view('productos.index', compact('productos'));
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $productos = Producto::all();
        return view('productos.create', compact('productos'));
    }


    public function store(Request $request)
    {

        $Data = $request->validate([
            'codigo' => ['required', 'string', 'max:20', 'unique:productos,codigo'],
            'nombre' => ['required', 'string', 'max:100'],
            'stock_actual' => ['required', 'integer', 'min:0'],
            'precio_compra' => ['required', 'numeric', 'min:0'],
            'precio_venta' => ['required', 'numeric', 'min:0'],
        ]);
        $Data['local_id'] = Auth::user()->local_id;
        Producto::create($Data);

        return redirect()
            ->route('productos.index')
            ->with('success', 'Producto creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Producto $producto)
    {
        return view('productos.show', compact('producto', 'productos'));

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Producto $producto)
    {
        return view('productos.edit', compact('producto'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Producto $producto)
    {
        $validatedData = $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'stock_actual' => ['required', 'integer', 'min:0'],
            'precio_compra' => ['required', 'numeric', 'min:0'],
            'precio_venta' => ['required', 'numeric', 'min:0'],
        ]);
        $producto->update($validatedData);

        return redirect()
            ->route('productos.index')
            ->with('success', 'Producto actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Producto $producto)
    {
        $producto->delete();
        return redirect()->route('productos.index')->with('success', 'Producto eliminado exitosamente.');
    }
}
