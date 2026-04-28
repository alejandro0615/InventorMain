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
     * Anula una salida, restaura el stock y elimina los registros.
     */
    public function cancelSalida(int $salidaId): void
    {
        $salida = Salida::with('detalles.producto')->findOrFail($salidaId);

        DB::transaction(function () use ($salida) {
            foreach ($salida->detalles as $detalle) {
                // Restaurar el stock al inventario
                $detalle->producto->increment('stock_actual', $detalle->cantidad);
                $detalle->delete();
            }

            DB::table('movimientos')->where('salida_id', $salida->id)->delete();

            $salida->delete();
        });
    }
}
