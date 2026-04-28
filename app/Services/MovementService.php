<?php

namespace App\Services;

use App\Models\Producto;
use App\Models\Salida;
use App\Models\SalidaDetalle;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

/**
 * Servicio para gestionar los movimientos de inventario (Entradas y Salidas).
 */
class MovementService
{
    /**
     * Registra un movimiento (Entrada o Salida) y actualiza el stock.
     * Si es una salida, crea también la factura (Salida) y sus detalles.
     */
    public function register(array $data): ?Salida
    {
        $salida = null;

        DB::transaction(function () use ($data, &$salida) {
            $localId = Auth::user()->local_id;
            $userId = Auth::id();

            // 1. Si es salida, creamos el registro principal de la factura
            if ($data['tipo_movimiento'] === 'salida') {
                $salida = Salida::create([
                    'cliente_id' => $data['proveedor_id'],
                    'user_id' => $userId,
                    'fecha' => $data['fecha'],
                    'notas' => $data['notas'] ?? null,
                    'iva_porcentaje' => 0,
                    'total' => 0,
                ]);
            }

            $subtotalGeneral = 0;
            $ivaGeneral = 0;

            // 2. Procesamos cada producto del movimiento
            foreach ($data['producto_id'] as $index => $productoId) {
                $cantidad = (int) $data['cantidad'][$index];
                $producto = Producto::where('local_id', $localId)->findOrFail($productoId);

                // Validar stock si es una salida
                if ($data['tipo_movimiento'] === 'salida' && $producto->stock_actual < $cantidad) {
                    throw ValidationException::withMessages([
                        "cantidad.{$index}" => "Stock insuficiente para {$producto->nombre}. Disponible: {$producto->stock_actual}",
                    ]);
                }

                $precioUnitario = (float) $data['precio'][$index];
                $ivaPorcentaje = (float) ($data['iva_porcentaje'][$index] ?? 0);

                // REGISTRAR EN TABLA MOVIMIENTOS
                \App\Models\Movimiento::create([
                    'local_id' => $localId,
                    'producto_id' => $productoId,
                    'proveedor_id' => $data['proveedor_id'],
                    'tipo_movimiento' => $data['tipo_movimiento'],
                    'fecha' => $data['fecha'],
                    'cantidad' => $cantidad,
                    'salida_id' => $salida ? $salida->id : null,
                ]);

                if ($data['tipo_movimiento'] === 'entrada') {
                    // Incrementar stock en entrada
                    $producto->increment('stock_actual', $cantidad);
                } else {
                    // Decrementar stock en salida
                    $producto->decrement('stock_actual', $cantidad);

                    if ($salida) {
                        $subtotalLinea = $cantidad * $precioUnitario;
                        $ivaLinea = $subtotalLinea * ($ivaPorcentaje / 100);

                        $subtotalGeneral += $subtotalLinea;
                        $ivaGeneral += $ivaLinea;

                        // Crear detalle de la factura
                        SalidaDetalle::create([
                            'salida_id' => $salida->id,
                            'producto_id' => $productoId,
                            'cantidad' => $cantidad,
                            'precio_unitario' => $precioUnitario,
                            'iva_porcentaje' => $ivaPorcentaje,
                            'subtotal' => $subtotalLinea,
                        ]);
                    }
                }
            }

            // 3. Actualizar totales y número de factura si aplica
            if ($salida) {
                $salida->total = $subtotalGeneral + $ivaGeneral;
                // Generamos un código de referencia único con marca de tiempo para evitar conflictos 409
                $salida->numero_factura = 'SETP' . $salida->id . '-' . time();
                $salida->save();
            }
        });

        return $salida;
    }

    /**
     * Anula un movimiento (Entrada o Salida), restaura el stock y elimina los registros.
     */
    public function cancelMovement(int $id): void
    {
        // Intentar encontrar en tabla movimientos primero
        $movimiento = \App\Models\Movimiento::find($id);

        if ($movimiento) {
            DB::transaction(function () use ($movimiento) {
                $producto = $movimiento->producto;
                
                if ($movimiento->tipo_movimiento === 'entrada') {
                    // Si era entrada, al borrarla restamos el stock
                    $producto->decrement('stock_actual', $movimiento->cantidad);
                } else {
                    // Si era salida, al borrarla sumamos el stock
                    $producto->increment('stock_actual', $movimiento->cantidad);
                }

                // Si tiene factura asociada, borrarla (opcional, depende de la lógica de negocio)
                if ($movimiento->salida_id) {
                    $salida = Salida::with('detalles')->find($movimiento->salida_id);
                    if ($salida) {
                        $salida->detalles()->delete();
                        $salida->delete();
                    }
                }

                $movimiento->delete();
            });
            return;
        }

        // Caso de respaldo: anulación por ID de Salida (antigua lógica)
        $salida = Salida::with('detalles.producto')->find($id);
        if ($salida) {
            DB::transaction(function () use ($salida) {
                foreach ($salida->detalles as $detalle) {
                    $detalle->producto->increment('stock_actual', $detalle->cantidad);
                    $detalle->delete();
                }
                \App\Models\Movimiento::where('salida_id', $salida->id)->delete();
                $salida->delete();
            });
        }
    }
}
