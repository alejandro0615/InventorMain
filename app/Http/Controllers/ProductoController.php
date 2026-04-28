<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controlador para la gestión del catálogo de productos.
 */
class ProductoController extends Controller
{
    /**
     * Lista los productos del local actual con filtros de búsqueda y stock.
     */
    public function index(Request $request)
    {
        $localId = Auth::user()->local_id;
        $search = $request->get('q');

        // Construir la consulta usando scopes del modelo
        $query = Producto::delLocal($localId)
            ->buscar($search);

        if ($request->get('filtro') === 'stock_bajo') {
            $query->stockBajo();
        }

        $productos = $query->paginate(10)->withQueryString();

        // Soporte para carga vía AJAX (SPA-like)
        if ($request->ajax()) {
            return view('productos._content', compact('productos', 'search'));
        }

        return view('productos.index', compact('productos', 'search'));
    }

    /**
     * Muestra el formulario para crear un nuevo producto.
     */
    public function create()
    {
        return view('productos.create');
    }

    /**
     * Almacena un nuevo producto en la base de datos.
     */
    public function store(Request $request)
    {
        $localId = Auth::user()->local_id;

        $data = $request->validate([
            'codigo'         => [
                'required', 'string', 'max:20', 
                'unique:productos,codigo,NULL,id,local_id,' . $localId
            ],
            'nombre'         => ['required', 'string', 'max:100'],
            'stock_actual'   => ['required', 'integer', 'min:0'],
            'precio_compra'  => ['required', 'numeric', 'min:0'],
            'precio_venta'   => ['required', 'numeric', 'min:0'],
        ]);

        $data['local_id'] = $localId;
        Producto::create($data);

        return redirect()
            ->route('productos.index')
            ->with('success', 'Producto creado correctamente.');
    }

    /**
     * Muestra el detalle de un producto específico.
     */
    public function show(Producto $producto)
    {
        // Asegurar que el producto pertenece al local del usuario
        if ($producto->local_id !== Auth::user()->local_id) {
            abort(403);
        }

        return view('productos.show', compact('producto'));
    }

    /**
     * Muestra el formulario de edición de un producto.
     */
    public function edit(Producto $producto)
    {
        if ($producto->local_id !== Auth::user()->local_id) {
            abort(403);
        }

        return view('productos.edit', compact('producto'));
    }

    /**
     * Actualiza la información de un producto.
     */
    public function update(Request $request, Producto $producto)
    {
        if ($producto->local_id !== Auth::user()->local_id) {
            abort(403);
        }

        $validatedData = $request->validate([
            'nombre'        => ['required', 'string', 'max:100'],
            'stock_actual'  => ['required', 'integer', 'min:0'],
            'precio_compra' => ['required', 'numeric', 'min:0'],
            'precio_venta'  => ['required', 'numeric', 'min:0'],
        ]);

        $producto->update($validatedData);

        return redirect()
            ->route('productos.index')
            ->with('success', 'Producto actualizado correctamente.');
    }

    /**
     * Elimina un producto del inventario.
     */
    public function destroy(Producto $producto)
    {
        if ($producto->local_id !== Auth::user()->local_id) {
            abort(403);
        }

        $producto->delete();

        return redirect()
            ->route('productos.index')
            ->with('success', 'Producto eliminado exitosamente.');
    }
}
