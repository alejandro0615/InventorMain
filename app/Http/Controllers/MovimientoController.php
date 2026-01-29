<?php

namespace App\Http\Controllers;

use App\Models\Movimiento;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\Salida;
use App\Models\SalidaDetalle;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MovimientoController extends Controller
{
    public function index(Request $request)
    {
        $localId = Auth::user()->local_id;

        $proveedorId = $request->input('proveedor_id');
        $fecha = $request->input('fecha');

        // 👉 ESTO FALTABA
        $proveedores = Proveedor::where('local_id', $localId)
            ->orderBy('nombre')
            ->get();

        $movimientos = Salida::with(['detalles.producto', 'cliente'])
            ->whereHas('cliente', function ($q) use ($localId) {
                $q->where('local_id', $localId);
            })
            ->when(
                $proveedorId,
                fn($q) => $q->where('cliente_id', $proveedorId)
            )
            ->when(
                $fecha,
                fn($q) => $q->whereDate('fecha', $fecha)
            )
            ->orderBy('fecha', 'desc')
            ->get();

        return view(
            'movimientos.index',
            compact('movimientos', 'proveedores', 'proveedorId', 'fecha')
        );
    }



    public function create()
    {

        $localId = Auth::user()->local_id;
        $productos = Producto::where('local_id', $localId)->get();
        $proveedores = Proveedor::where('local_id', $localId)->get();
        // Mostrar formulario para crear un nuevo movimiento
        return view('movimientos.create', compact('productos', 'proveedores'));
    }

    public function store(Request $request)
    {

        $tipo_movimiento = ['entrada', 'salida'];


        $data = $request->validate(
            [
                'proveedor_id' => ['required', 'exists:proveedores,id'],
                'fecha' => ['required', 'date'],
                'tipo_movimiento' => ['required', 'in:' . implode(',', $tipo_movimiento)],
                'producto_id' => ['required', 'array'],
                'producto_id.*' => ['required', 'exists:productos,id'],
                'cantidad' => ['required', 'array'],
                'cantidad.*' => ['required', 'integer', 'min:1'],
                // Campo opcional para notas/observaciones de la factura/salida
                'notas' => ['nullable', 'string'],
            ],
            [
                'proveedor_id.required' => 'El campo proveedor es obligatorio.',
                'proveedor_id.exists' => 'El proveedor seleccionado no es válido.',
                'fecha.required' => 'El campo fecha es obligatorio.',
                'fecha.date' => 'El campo fecha debe ser una fecha válida.',
                'tipo_movimiento.required' => 'El tipo de movimiento es obligatorio.',
                'tipo_movimiento.in' => 'El tipo de movimiento debe ser "entrada" o "salida".',

                'producto_id.required' => 'Debes agregar al menos un producto.',
                'producto_id.array' => 'Los productos deben enviarse como lista.',
                'producto_id.*.exists' => 'Algún producto seleccionado no es válido.',

                'cantidad.required' => 'Debes indicar cantidades.',
                'cantidad.array' => 'Las cantidades deben enviarse como lista.',
                'cantidad.*.required' => 'Todas las cantidades son obligatorias.',
                'cantidad.*.integer' => 'Cada cantidad debe ser un número entero.',
                'cantidad.*.min' => 'Cada cantidad debe ser al menos 1.',
            ]

        );
        // Variable para poder usar la salida creada fuera de la transacción
        $salida = null;

        DB::transaction(function () use ($data, $request, &$salida) {
            $localId = Auth::user()->local_id;

            // Si el movimiento es de SALIDA, creamos el encabezado de factura/salida
            if ($data['tipo_movimiento'] === 'salida') {
                $salida = Salida::create([
                    'cliente_id' => $data['proveedor_id'], // usamos proveedor como cliente
                    'user_id' => Auth::id(),
                    'fecha' => $data['fecha'],
                    'notas' => $request->input('notas'),
                    // IVA general opcional, por ahora lo dejamos en 0 y se calcula por detalle
                    'iva_porcentaje' => 0,
                    'total' => 0,
                ]);
            }

            // Acumuladores para el total de la factura
            $subtotalGeneral = 0;
            $ivaGeneral = 0;

            // Recorremos cada línea de producto
            foreach ($data['producto_id'] as $index => $productoId) {
                $cantidad = (int) $data['cantidad'][$index];

                $producto = Producto::where('local_id', $localId)
                    ->findOrFail($productoId);

                // Validar stock para salidas
                if ($data['tipo_movimiento'] === 'salida') {
                    if ($producto->stock_actual == 0 || $producto->stock_actual < $cantidad) {
                        throw \Illuminate\Validation\ValidationException::withMessages([
                            'cantidad.' . $index => 'No hay suficiente stock para ' .
                                $producto->nombre . '. Stock actual: ' . $producto->stock_actual,
                        ]);
                    }
                }

                $pivotData = [
                    'tipo_movimiento' => $data['tipo_movimiento'],
                    'cantidad' => $cantidad,
                    'fecha' => $data['fecha'],
                    'local_id' => Auth::user()->local_id,
                ];

                // Si se generó una salida/factura, guardamos también el ID en el pivote
                if ($salida && $data['tipo_movimiento'] === 'salida') {
                    $pivotData['salida_id'] = $salida->id;
                }

                $producto->proveedores()->attach($data['proveedor_id'], $pivotData);
                // Actualizar stock
                if ($data['tipo_movimiento'] === 'entrada') {
                    $producto->increment('stock_actual', $cantidad);
                } else { // salida
                    $producto->decrement('stock_actual', $cantidad);

                    // Si tenemos una salida (factura), creamos el detalle de la misma
                    if ($salida) {
                        // Usamos el precio de venta del producto como precio unitario
                        $precioUnitario = $producto->precio_venta;

                        // IVA por defecto para cada línea (ajusta el porcentaje según tu necesidad)
                        $ivaPorcentaje = 0; // Ejemplo: 19 para 19%

                        $subtotalLinea = $cantidad * $precioUnitario;
                        $ivaLinea = $subtotalLinea * ($ivaPorcentaje / 100);

                        $subtotalGeneral += $subtotalLinea;
                        $ivaGeneral += $ivaLinea;

                        SalidaDetalle::create([
                            'salida_id' => $salida->id,
                            'producto_id' => $productoId,
                            'cantidad' => $cantidad,
                            'precio_unitario' => $precioUnitario,
                            'iva_porcentaje' => $ivaPorcentaje,
                            // Guardamos el subtotal sin IVA
                            'subtotal' => $subtotalLinea,
                        ]);
                    }
                }
            }

            // Actualizamos totales de la salida/factura si se creó
            if ($salida) {
                $salida->total = $subtotalGeneral + $ivaGeneral;

                // Generamos un número de factura sencillo y único basado en el ID
                $salida->numero_factura = 'F' . str_pad($salida->id, 6, '0', STR_PAD_LEFT);

                $salida->save();
            }
        });

        // Si el movimiento fue una salida y se creó la factura, redirigimos al PDF
        if ($data['tipo_movimiento'] === 'salida' && $salida) {
            return redirect()
                ->route('salidas.factura', $salida)
                ->with('success', 'Movimiento de salida registrado y factura generada correctamente.');
        }

        // Para entradas seguimos con el flujo normal
        return redirect()
            ->route('movimientos.index')
            ->with('success', 'Movimiento registrado exitosamente y stock actualizado.');
    }

    public function show($id)
    {
        $producto = Producto::with('proveedores')
            ->where('local_id', Auth::user()->local_id)
            ->findOrFail($id);

        return view('movimientos.show', compact('producto'));
    }

    public function edit($id)
    {
        $producto = Producto::with('proveedores')
            ->where('local_id', Auth::user()->local_id)
            ->findOrFail($id);
        $proveedores = Proveedor::all();
        $movimientos_asociados = $producto->proveedores->pluck('id')->toArray();
        return view('movimientos.edit', compact('producto', 'movimientos_asociados'));
    }

    public function update(Request $request, $id)
    {

        $producto = Producto::where('local_id', Auth::user()->local_id)
            ->findOrFail($id);


        $data = $request->validate([
            'proveedor_id' => ['required', 'exists:proveedores,id'],
            'cantidad' => ['required', 'integer', 'min:1'],
            'fecha' => ['required', 'date'],
            'tipo_movimiento' => ['required', 'in:entrada,salida'],
        ]);


        if ($tipo_movimiento = 'salida') {
            if ($producto->stock == 0 || $producto->stock < $data['cantidad']) {
                return redirect()->back()->withErrors([
                    'cantidad' => 'No hay suficiente stock para realizar 
                la salida. Stock actual: ' . $producto->stock
                ])->withInput();
            }
        }

        $producto->proveedores()->updateExistingPivot($data['proveedor_id'], [
            'stock_actual' => $data['stock_actual'],
            'fecha' => $data['fecha'],
            'tipo_movimiento' => $data['tipo_movimiento'],
            'local_id' => Auth::user()->local_id,
        ]);


        if ($data['tipo_movimiento'] == 'entrada') {
            $producto->increment('stock_actual', $data['stock_actual']);
        } else {
            $producto->decrement('stock_actual', $data['stock_actual']);
        }

        $producto->save();

        return redirect()->route('movimientos.index')->with('success', 'Movimiento actualizado exitosamente.');

    }

    public function destroy($id)
    {
        $producto = Producto::where('local_id', Auth::user()->local_id)
            ->findOrFail($id);

        $producto->proveedores()->detach();
        return redirect()->route('movimientos.index')->with('success', 'Movimiento eliminado exitosamente.');
    }
}
