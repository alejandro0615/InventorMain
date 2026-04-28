<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Ticket {{ $salida->numero_factura ?? $salida->id }}</title>
    <style>
        @page {
            margin: 0;
        }

        body {
            font-family: 'Courier', monospace;
            font-size: 10px;
            margin: 0;
            padding: 15px; /* Más margen externo */
            width: 80mm;
            color: #000;
            line-height: 1.5; /* Más espacio entre líneas */
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .bold { font-weight: bold; }
        
        .divider { 
            border-top: 1px dashed #000; 
            margin: 12px 0; /* Más espacio en los divisores */
        }

        .header h2 { font-size: 16px; margin-bottom: 5px; }
        .header p { margin: 2px 0; font-size: 10px; }

        .info { 
            margin-bottom: 15px; 
            font-size: 10px;
            line-height: 1.4;
        }
        .info p { margin: 4px 0; }

        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 15px; 
        }
        th { 
            border-bottom: 1px solid #000; 
            text-align: left; 
            padding: 8px 0; /* Más espacio en cabecera */
            font-size: 10px;
        }
        td { 
            padding: 6px 0; /* Más espacio entre productos */
            vertical-align: top; 
            font-size: 10px;
        }

        .totals { 
            margin-top: 10px; 
            padding: 5px 0;
        }
        .totals-row { 
            display: flex; 
            justify-content: space-between; 
            margin: 6px 0; /* Más espacio en filas de totales */
        }
        .total-final { 
            font-size: 14px; 
            border-top: 2px double #000; 
            padding-top: 10px; 
            margin-top: 10px;
        }

        .qr-section { 
            margin-top: 20px; 
            text-align: center; 
            padding: 10px 0;
        }
        .qr-section p {
            font-size: 8px;
            line-height: 1.2;
            word-break: break-all;
        }

        .footer { 
            margin-top: 25px; 
            font-size: 9px; 
            line-height: 1.4;
            padding-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="header text-center">
        <h2>{{ $salida->user->negocio->nombre ?? 'MI NEGOCIO' }}</h2>
        <p class="bold">NIT: {{ $salida->user->nit ?? 'N/A' }}</p>
        <p>{{ $salida->user->negocio->direccion ?? '' }}</p>
        <p>Tel: {{ $salida->user->telefono ?? '' }}</p>
        <div class="divider"></div>
        <p class="bold" style="font-size: 12px;">FACTURA DE VENTA</p>
        <p class="bold" style="font-size: 12px;">{{ $salida->numero_factura ?? 'TEMP-'.$salida->id }}</p>
        <p>Fecha: {{ \Carbon\Carbon::parse($salida->fecha)->format('d/m/Y H:i') }}</p>
    </div>

    <div class="divider"></div>

    <div class="info">
        <p><span class="bold">CLIENTE :</span> {{ $salida->cliente->nombre ?? 'Consumidor Final' }}</p>
        <p><span class="bold">NIT/CC  :</span> {{ $salida->cliente->nit ?? '222222222222' }}</p>
        <p><span class="bold">CIUDAD  :</span> {{ $salida->cliente->ciudad ?? 'N/A' }}</p>
        <p><span class="bold">VENDEDOR:</span> {{ $salida->user->name }}</p>
    </div>

    <div class="divider"></div>

    <table>
        <thead>
            <tr>
                <th style="width: 50%;">DESCRIPCIÓN</th>
                <th style="width: 15%; text-align: center;">CANT</th>
                <th style="width: 35%; text-align: right;">TOTAL</th>
            </tr>
        </thead>
        <tbody>
            @php
                $subtotalGeneral = 0;
                $ivaGeneral = 0;
            @endphp
            @foreach ($salida->detalles as $detalle)
                @php
                    $base = $detalle->cantidad * $detalle->precio_unitario;
                    $iva = $base * (($detalle->iva_porcentaje ?? 0) / 100);
                    $subtotalGeneral += $base;
                    $ivaGeneral += $iva;
                @endphp
                <tr>
                    <td>{{ $detalle->producto->nombre }}</td>
                    <td class="text-center">{{ $detalle->cantidad }}</td>
                    <td class="text-right">$ {{ number_format($base, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="divider"></div>

    <div class="totals">
        <div class="totals-row">
            <span>SUBTOTAL:</span>
            <span>$ {{ number_format($subtotalGeneral, 0, ',', '.') }}</span>
        </div>
        <div class="totals-row">
            <span>IVA:</span>
            <span>$ {{ number_format($ivaGeneral, 0, ',', '.') }}</span>
        </div>
        <div class="totals-row total-final bold">
            <span>TOTAL A PAGAR:</span>
            <span>$ {{ number_format($subtotalGeneral + $ivaGeneral, 0, ',', '.') }}</span>
        </div>
    </div>

    @if($salida->cufe)
    <div class="qr-section">
        <p class="bold">CUFE:</p>
        <p>{{ $salida->cufe }}</p>
    </div>
    @endif

    <div class="footer text-center">
        <div class="divider"></div>
        <p>¡Gracias por su compra!</p>
        <p>Representación gráfica de factura electrónica</p>
        <p>Software de Inventario Pro</p>
    </div>
</body>

</html>
