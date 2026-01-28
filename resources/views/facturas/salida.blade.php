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
            color: white;
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

        /* Print styles */
        @media print {
            body {
                margin: 0;
                padding: 0;
            }

            .container {
                max-width: 100%;
                padding: 10px;
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
                        @if($salida->user && $salida->user->negocio)
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
                @if($salida->cliente)
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
                @if($salida->user)
                    <p><strong>Nombre:</strong> {{ $salida->user->name }}</p>
                    <p><strong>Email:</strong> {{ $salida->user->email }}</p>
                    @if($salida->user->razon_social)
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
            <table>
                <thead>
                    <tr>
                        <th style="width: 10%;">Código</th>
                        <th style="width: 35%;">Producto</th>
                        <th style="width: 10%;" class="text-center">Cantidad</th>
                        <th style="width: 15%;" class="text-right">Precio Unit.</th>
                        <th style="width: 10%;" class="text-center">IVA %</th>
                        <th style="width: 20%;" class="text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $subtotalGeneral = 0;
                        $ivaGeneral = 0;
                    @endphp
                    @forelse($salida->detalles as $detalle)
                        @php
                            $subtotal = $detalle->cantidad * $detalle->precio_unitario;
                            $iva = $subtotal * ($detalle->iva_porcentaje / 100);
                            $subtotalGeneral += $subtotal;
                            $ivaGeneral += $iva;
                        @endphp
                        <tr>
                            <td>{{ $detalle->producto->id ?? 'N/A' }}</td>
                            <td>{{ $detalle->producto->nombre ?? 'Producto no encontrado' }}</td>
                            <td class="text-center">{{ $detalle->cantidad }}</td>
                            <td class="text-right">${{ number_format($detalle->precio_unitario, 2, ',', '.') }}</td>
                            <td class="text-center">{{ $detalle->iva_porcentaje }}%</td>
                            <td class="text-right">${{ number_format($subtotal, 2, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No hay productos en esta salida</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Totals -->
        <div class="totals-section">
            <div class="totals-box">
                <div class="totals-row subtotal">
                    <span class="totals-label">Subtotal:</span>
                    <span class="totals-value">${{ number_format($subtotalGeneral, 2, ',', '.') }}</span>
                </div>
                <div class="totals-row iva">
                    <span class="totals-label">IVA:</span>
                    <span class="totals-value">${{ number_format($ivaGeneral, 2, ',', '.') }}</span>
                </div>
                <div class="totals-row total">
                    <span class="totals-label">TOTAL:</span>
                    <span class="totals-value">${{ number_format($subtotalGeneral + $ivaGeneral, 2, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Notes -->
        @if($salida->notas)
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