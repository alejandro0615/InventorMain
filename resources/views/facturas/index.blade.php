<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Factura #{{ $salida->id }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 4px; }
        th { background: #f3f4f6; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <h2>Factura de salida #{{ $salida->id }}</h2>
    <p>Fecha: {{ $salida->fecha->format('d/m/Y') }}</p>
    <p>Cliente: {{ $salida->cliente->nombre ?? 'Consumidor final' }}</p>

    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cant.</th>
                <th>Precio</th>
                <th>IVA %</th>
                <th>IVA</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @php
                $subtotal = 0;
                $totalIva = 0;
            @endphp
            @foreach($salida->detalles as $detalle)
                @php
                    $base = $detalle->cantidad * $detalle->precio_unitario;
                    $ivaPct = $detalle->iva_porcentaje ?? 0;
                    $ivaVal = $base * ($ivaPct / 100);
                    $subtotal += $base;
                    $totalIva += $ivaVal;
                @endphp
                <tr>
                    <td>{{ $detalle->producto->codigo }} - {{ $detalle->producto->nombre }}</td>
                    <td class="text-right">{{ $detalle->cantidad }}</td>
                    <td class="text-right">{{ number_format($detalle->precio_unitario, 0, ',', '.') }}</td>
                    <td class="text-right">{{ $ivaPct }}%</td>
                    <td class="text-right">{{ number_format($ivaVal, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($base, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="5" class="text-right">Subtotal sin IVA:</th>
                <th class="text-right">{{ number_format($subtotal, 0, ',', '.') }}</th>
            </tr>
            <tr>
                <th colspan="5" class="text-right">Total IVA:</th>
                <th class="text-right">{{ number_format($totalIva, 0, ',', '.') }}</th>
            </tr>
            <tr>
                <th colspan="5" class="text-right">Total:</th>
                <th class="text-right">{{ number_format($subtotal + $totalIva, 0, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>
</body>
</html>
