<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura {{ $salida->numero_factura ?? 'N/A' }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.4;
        }

        .container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Header */
        .header {
            border-bottom: 3px solid #2563eb;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 10px;
        }

        .company-info {
            flex: 1;
        }

        .company-name {
            font-size: 20px;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 5px;
        }

        .company-details {
            font-size: 10px;
            color: #666;
            line-height: 1.6;
        }

        .invoice-badge {
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-align: right;
        }

        .invoice-badge h1 {
            font-size: 18px;
            margin-bottom: 3px;
        }

        .invoice-badge p {
            font-size: 11px;
            opacity: 0.9;
        }

        /* Info Sections */
        .info-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            gap: 15px;
        }

        .info-box {
            flex: 1;
            border: 1px solid #e5e7eb;
            border-radius: 5px;
            padding: 12px;
            background: #f9fafb;
        }

        .info-box h3 {
            font-size: 11px;
            color: #2563eb;
            text-transform: uppercase;
            margin-bottom: 8px;
            font-weight: 600;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 5px;
        }

        .info-box p {
            font-size: 10px;
            margin-bottom: 3px;
            color: #4b5563;
        }

        .info-box strong {
            color: #1f2937;
            font-weight: 600;
        }

        /* Table */
        .table-container {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #e5e7eb;
        }

        thead {
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
        }

        thead th {
            padding: 10px 8px;
            text-align: left;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
        }

        tbody tr {
            border-bottom: 1px solid #e5e7eb;
        }

        tbody tr:nth-child(even) {
            background: #f9fafb;
        }

        tbody tr:hover {
            background: #f3f4f6;
        }

        tbody td {
            padding: 8px;
            font-size: 10px;
            color: #4b5563;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .tabla-productos {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            /* 👈 clave para alineación */
        }

        .tabla-productos th,
        .tabla-productos td {
            border: 1px solid #e5e7eb;
            padding: 6px;
            font-size: 10px;
            vertical-align: middle;
            word-wrap: break-word;
        }

        /* Totals */
        .totals-section {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 20px;
        }

        .totals-box {
            width: 300px;
            border: 1px solid #e5e7eb;
            border-radius: 5px;
            overflow: hidden;
        }

        .totals-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 12px;
            border-bottom: 1px solid #e5e7eb;
        }

        .totals-row:last-child {
            border-bottom: none;
        }

        .totals-row.subtotal {
            background: #f9fafb;
        }

        .totals-row.iva {
            background: #f3f4f6;
        }

        .totals-row.total {
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            color: white;
            font-weight: bold;
            font-size: 13px;
        }

        .totals-label {
            font-size: 11px;
        }

        .totals-value {
            font-size: 11px;
            font-weight: 600;
        }

        /* Notes */
        .notes-section {
            border: 1px solid #e5e7eb;
            border-radius: 5px;
            padding: 12px;
            background: #fffbeb;
            margin-bottom: 20px;
        }

        .notes-section h3 {
            font-size: 11px;
            color: #92400e;
            margin-bottom: 5px;
            font-weight: 600;
        }

        .notes-section p {
            font-size: 10px;
            color: #78350f;
            line-height: 1.5;
        }

        /* Footer */
        .footer {
            border-top: 2px solid #e5e7eb;
            padding-top: 15px;
            text-align: center;
            font-size: 9px;
            color: #9ca3af;
        }

        .footer p {
            margin-bottom: 3px;
        }

        @media print {

            /* Forzar que se impriman los colores */
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            /* Reemplazar gradientes por colores sólidos */
            .invoice-badge,
            thead,
            .totals-row.total {
                background: #1e40af !important;
                color: #0e0e0e !important;
            }

            /* Fondos suaves para no perder texto */
            .info-box,
            tbody tr:nth-child(even),
            .notes-section {
                background: #f3f4f6 !important;
            }

            /* Bordes más visibles */
            table,
            th,
            td {
                border: 1px solid #9ca3af !important;
            }

            body {
                color: #000 !important;
            }
        }

        @media print and (max-width: 80mm) {

            body {
                font-size: 11px;
            }

            .container {
                max-width: 80mm;
                padding: 5px;
            }

            .header-top,
            .info-section {
                flex-direction: column;
                gap: 8px;
            }

            table thead {
                display: none;
            }

            table,
            tbody,
            tr,
            td {
                display: block;
                width: 100%;
            }

            tbody tr {
                border-bottom: 1px dashed #000;
                margin-bottom: 5px;
            }

            tbody td {
                text-align: left !important;
                padding: 3px 0;
            }

            .totals-section table {
                width: 100%;
                font-size: 12px;
            }

            .invoice-badge {
                text-align: center;
                padding: 6px;
                font-size: 12px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="header-top">
                <div class="company-info">
                    <div class="company-name">
                        {{ $salida->user->negocio->nombre ?? 'Mi Empresa' }}
                    </div>
                    <div class="company-details">
                        @if ($salida->user && $salida->user->negocio)
                            <p><strong>NIT:</strong> {{ $salida->user->nit ?? 'N/A' }}</p>
                            <p><strong>Dirección:</strong> {{ $salida->user->negocio->direccion ?? 'N/A' }}</p>
                            <p><strong>Teléfono:</strong> {{ $salida->user->telefono ?? 'N/A' }}</p>
                            <p><strong>Email:</strong> {{ $salida->user->email ?? 'N/A' }}</p>
                        @endif
                    </div>
                </div>
                <div class="invoice-badge">
                    <h1>FACTURA DE VENTA</h1>
                    <p>{{ $salida->numero_factura ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <!-- Info Section -->
        <div class="info-section">
            <!-- Cliente -->
            <div class="info-box">
                <h3>Información del Cliente</h3>
                @if ($salida->cliente)
                    <p><strong>Nombre:</strong> {{ $salida->cliente->nombre }}</p>
                    <p><strong>NIT:</strong> {{ $salida->cliente->nit ?? 'N/A' }}</p>
                    <p><strong>Dirección:</strong> {{ $salida->cliente->direccion ?? 'N/A' }}</p>
                    <p><strong>Teléfono:</strong> {{ $salida->cliente->telefono ?? 'N/A' }}</p>
                    <p><strong>Email:</strong> {{ $salida->cliente->email ?? 'N/A' }}</p>
                @else
                    <p>Cliente no especificado</p>
                @endif
            </div>

            <!-- Vendedor -->
            <div class="info-box">
                <h3>Información del Vendedor</h3>
                @if ($salida->user)
                    <p><strong>Nombre:</strong> {{ $salida->user->name }}</p>
                    <p><strong>Email:</strong> {{ $salida->user->email }}</p>
                    @if ($salida->user->razon_social)
                        <p><strong>Razón Social:</strong> {{ $salida->user->razon_social }}</p>
                    @endif
                @else
                    <p>Vendedor no especificado</p>
                @endif
                <p><strong>Fecha:</strong>
                    {{ $salida->fecha ? \Carbon\Carbon::parse($salida->fecha)->format('d/m/Y') : \Carbon\Carbon::now()->format('d/m/Y') }}
                </p>
            </div>
        </div>

        <!-- Products Table -->
        <div class="table-container">
            <table class="tabla-productos">
                <thead>
                    <tr>
                        <th style="width:12%; text-align:center;">CÓDIGO</th>
                        <th style="width:38%; text-align:left;">PRODUCTO</th>
                        <th style="width:8%; text-align:center;">CANT</th>
                        <th style="width:14%; text-align:right;">PRECIO U</th>
                        <th style="width:8%; text-align:center;">IVA %</th>
                        <th style="width:20%; text-align:right;">SUBTOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $subtotalGeneral = 0;
                        $ivaGeneral = 0;
                    @endphp

                    @foreach ($salida->detalles as $detalle)
                        @php
                            $producto = $detalle->producto;
                            $base = $detalle->cantidad * $detalle->precio_unitario;
                            $iva = $base * ($detalle->iva_porcentaje / 100);
                            $subtotalGeneral += $base;
                            $ivaGeneral += $iva;
                        @endphp
                        <tr>
                            <td style="text-align:center;">
                                {{ $producto->codigo ?? '—' }}
                            </td>

                            <td>
                                {{ $producto->nombre ?? 'Producto no encontrado' }}
                            </td>

                            <td style="text-align:center;">
                                {{ $detalle->cantidad }}
                            </td>

                            <td style="text-align:right;">
                                ${{ number_format($detalle->precio_unitario, 2, ',', '.') }}
                            </td>

                            <td style="text-align:center;">
                                {{ $detalle->iva_porcentaje }}%
                            </td>

                            <td style="text-align:right;">
                                ${{ number_format($base, 2, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>


        <!-- Totals -->
        <div class="totals-section">
            <table style="width: 320px; border-collapse: collapse; font-size: 11px;">
                <thead>
                    <tr>
                        <th style="border:1px solid #e5e7eb; padding:6px; text-align:left; background:#f3f4f6;">
                            Concepto
                        </th>
                        <th style="border:1px solid #e5e7eb; padding:6px; text-align:right; background:#f3f4f6;">
                            Valor
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="border:1px solid #e5e7eb; padding:6px;">Subtotal sin IVA</td>
                        <td style="border:1px solid #e5e7eb; padding:6px; text-align:right;">
                            ${{ number_format($subtotalGeneral, 2, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td style="border:1px solid #e5e7eb; padding:6px;">Total IVA</td>
                        <td style="border:1px solid #e5e7eb; padding:6px; text-align:right;">
                            ${{ number_format($ivaGeneral, 2, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td style="border:1px solid #e5e7eb; padding:6px; font-weight:bold; background:#e0ecff;">
                            TOTAL A PAGAR
                        </td>
                        <td
                            style="border:1px solid #e5e7eb; padding:6px; text-align:right; font-weight:bold; background:#e0ecff;">
                            ${{ number_format($subtotalGeneral + $ivaGeneral, 2, ',', '.') }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        @php
            $ivaPorGrupo = [];
            foreach ($salida->detalles as $detalle) {
                $base = $detalle->cantidad * $detalle->precio_unitario;
                $pct = $detalle->iva_porcentaje ?? 0;
                $ivaV = $base * ($pct / 100);
                if (!isset($ivaPorGrupo[$pct])) {
                    $ivaPorGrupo[$pct] = ['base' => 0, 'iva' => 0];
                }
                $ivaPorGrupo[$pct]['base'] += $base;
                $ivaPorGrupo[$pct]['iva'] += $ivaV;
            }
        @endphp

        @if (count($ivaPorGrupo))
            <div style="display:flex; justify-content:flex-end; margin-top:6px;">
                <table style="width: 320px; border-collapse: collapse; font-size: 10px;">
                    <thead>
                        <tr>
                            <th style="border:1px solid #515c71; padding:4px; background:#f9fafb;">IVA %</th>
                            <th style="border:1px solid #515c71; padding:4px; background:#f9fafb;">Base</th>
                            <th style="border:1px solid #515c71; padding:4px; background:#f9fafb;">IVA</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ivaPorGrupo as $pct => $grupo)
                            <tr>
                                <td style="border:1px solid #e5e7eb; padding:4px; text-align:center;">
                                    {{ $pct }}%
                                </td>
                                <td style="border:1px solid #e5e7eb; padding:4px; text-align:right;">
                                    ${{ number_format($grupo['base'], 2, ',', '.') }}
                                </td>
                                <td style="border:1px solid #e5e7eb; padding:4px; text-align:right;">
                                    ${{ number_format($grupo['iva'], 2, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <!-- Notes -->
        @if ($salida->notas)
            <div class="notes-section">
                <h3>Notas / Observaciones</h3>
                <p>{{ $salida->notas }}</p>
            </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <p>Este documento es una representación de una factura electrónica</p>
            <p>Generada el {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</p>
            <p>{{ $salida->user->negocio->nombre ?? 'Mi Empresa' }} - Todos los derechos reservados</p>
        </div>
    </div>
</body>

</html>
