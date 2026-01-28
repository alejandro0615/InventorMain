<?php

namespace App\Http\Controllers;

use App\Models\Cotizacion;
use App\Models\Cotizacion_detalle;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CotizacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $productos = Producto::where('local_id', Auth::user()->local_id)->get();

        if ($request->ajax()) {
            return view('cotizacion._content', compact('productos'));
        }

        return view('cotizacion.index', compact('productos'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'fecha' => ['required', 'date'],
            'producto_id' => ['required', 'array'],
            'producto_id.*' => ['required', 'exists:productos,id'],
            'cantidad' => ['required', 'array'],
            'cantidad.*' => ['required', 'integer', 'min:1'],
        ], [
            'producto_id.required' => 'Debes agregar al menos un producto.',
            'producto_id.*.exists' => 'Algún producto seleccionado no es válido.',
            'cantidad.*.integer' => 'Cada cantidad debe ser un número entero.',
            'cantidad.*.min' => 'Cada cantidad debe ser al menos 1.',
        ]);

        DB::transaction(function () use ($data) {
            $subtotal = 0;
            $localId = Auth::user()->local_id;

            $cotizacion = Cotizacion::create([
                'cliente_id' => null,
                'fecha' => $data['fecha'],
                'subtotal' => 0,
                'impuestos' => 0,
                'total' => 0,
                'local_id' => $localId,
            ]);

            foreach ($data['producto_id'] as $i => $productoId) {
                $cantidad = (int) $data['cantidad'][$i];

                $producto = Producto::where('local_id', $localId)
                    ->findOrFail($productoId);

                $precio = $producto->precio_venta; // o el campo de precio que tengas
                $sub = $cantidad * $precio;
                $subtotal += $sub;

                Cotizacion_detalle::create([
                    'cotizacion_id' => $cotizacion->id,
                    'producto_id' => $producto->id,
                    'cantidad' => $cantidad,
                    'precio_unitario' => $precio,
                    'subtotal' => $sub,
                ]);
            }

            $cotizacion->update([
                'subtotal' => $subtotal,
                'impuestos' => 0,
                'total' => $subtotal,
            ]);
        });

        return redirect()
            ->route('cotizacion.index')
            ->with('status', 'Cotización creada correctamente.');
    }


    /**
     * Display the specified resource.
     */
    public function show(Cotizacion $cotizacion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cotizacion $cotizacion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cotizacion $cotizacion)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cotizacion $cotizacion)
    {
        //
    }
}
