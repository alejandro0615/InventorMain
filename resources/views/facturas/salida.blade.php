<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Ticket {{ $salida->numero_factura ?? $salida->id }}</title>
    <style>
        @page {
            margin: 0;
        }

        @media print {
            body {
                width: 72mm;
            }
        }

        body {
            font-family: monospace;
            /* mejor que Courier en térmica */
            font-size: 9px;
            /* más compacto */
            margin: 0;
            padding: 5px;
            /* menos margen */
            width: 72mm;
            /* evita cortes reales en impresoras 80mm */
            color: #000;
            line-height: 1.2;
        }

        /* UTILIDADES */
        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .bold {
            font-weight: bold;
        }

        .divider {
            border-top: 1px dashed #000;
            margin: 6px 0;
        }

        /* HEADER */
        .header h2 {
            font-size: 12px;
            margin: 2px 0;
        }

        .header p {
            margin: 1px 0;
            font-size: 9px;
        }

        /* INFO EMPRESA / CLIENTE */
        .info {
            margin-bottom: 6px;
            font-size: 9px;
        }

        .info p {
            margin: 2px 0;
        }

        /* TABLA PRODUCTOS */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }

        th {
            border-bottom: 1px solid #000;
            text-align: left;
            padding: 4px 0;
            font-size: 9px;
        }

        tr {
            page-break-inside: avoid;
        }

        td {
            padding: 3px 0;
            vertical-align: top;
            font-size: 9px;
        }

        /* Evita que nombres largos dañen el formato */
        td.descripcion {
            display: block;
            max-height: 2.4em;
            overflow: hidden;
        }

        /* TOTALES */
        .totals {
            margin-top: 5px;
        }

        .totals-row {
            display: flex;
            justify-content: space-between;
            margin: 2px 0;
        }

        .total-final {
            font-size: 11px;
            border-top: 1px solid #000;
            padding-top: 5px;
            margin-top: 5px;
        }

        /* QR */
        .qr-section {
            margin-top: 10px;
            text-align: center;
        }

        .qr-section img {
            width: 90px;
        }

        .qr-section p {
            font-size: 7px;
            line-height: 1.1;
            white-space: normal;
            overflow-wrap: anywhere;
            word-break: break-word;
            max-width: 100%;
        }

        /* FOOTER DIAN */
        .footer {
            margin-top: 10px;
            font-size: 8px;
            line-height: 1.2;
        }
    </style>
</head>

<body>
    <div class="header text-center">
        <h2>{{ $salida->user?->negocio?->nombre ?? 'MI NEGOCIO' }}</h2>
        <p class="bold">NIT: {{ $salida->user->nit ?? 'N/A' }}</p>
        <p>{{ $salida->user?->negocio?->direccion ?? '' }}</p>
        <p>Tel: {{ $salida->user->telefono ?? '' }}</p>
        <div class="divider"></div>
        <p class="bold" style="font-size: 12px;">FACTURA DE VENTA</p>
        <p class="bold" style="font-size: 12px;">{{ $salida->numero_factura ?? 'TEMP-' . $salida->id }}</p>
        <p>Fecha: {{ \Carbon\Carbon::parse($salida->fecha)->format('d/m/Y H:i') }}</p>
        <p>ID: {{ $salida->id }}</p>
    </div>

    <div class="divider"></div>

    <div class="info">
        <p><span class="bold">CLIENTE :</span> {{ $salida->cliente->nombre ?? 'Consumidor Final' }}</p>
        <p><span class="bold">NIT/CC :</span> {{ $salida->cliente->nit ?? '222222222222' }}</p>
        <p><span class="bold">DIRECCIÓN :</span> {{ $salida->cliente->direccion ?? 'N/A' }}</p>
        <p><span class="bold">VENDEDOR:</span> {{ $salida->user->name }}</p>
        <p><span class="bold">PAGO:</span> {{ $salida->metodo_pago ?? 'EFECTIVO' }}</p>
    </div>

    <div class="divider"></div>

    <table>
        <thead>
            <tr>
                <th>DESCRIPCIÓN</th>
                <th class="text-center">CANT</th>
                <th class="text-right">V.UNI</th>
                <th class="text-right">TOTAL</th>
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
                    <td class="text-right">$ {{ number_format($detalle->precio_unitario, 0, ',', '.') }}</td>
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
        @if ($ivaGeneral > 0)
            <div class="totals-row">
                <span>IVA (19%):</span>
                <span>$ {{ number_format($ivaGeneral, 0, ',', '.') }}</span>
            </div>
        @else
            <div class="totals-row">
                <span>IVA:</span>
                <span>EXENTO / EXCLUIDO</span>
            </div>
        @endif
        <div class="totals-row total-final bold">
            <span>TOTAL A PAGAR:</span>
            <span>$ {{ number_format($subtotalGeneral + $ivaGeneral, 0, ',', '.') }}</span>
        </div>
    </div>

    @if ($salida->cufe)
        <div class="qr-section">
            <p class="bold">CUFE:</p>
            <p>{{ $salida->cufe }}</p>
        </div>
    @endif

    <div class="footer text-center">
        <p>Documento equivalente a factura electrónica</p>
        <p>Validar en: https://catalogo-vpfe.dian.gov.co</p>
        <div class="divider"></div>
        <p>¡Gracias por su compra!</p>
        <p>Representación gráfica de factura electrónica</p>
        <p>Software de Inventario Pro</p>
    </div>
</body>

</html>
