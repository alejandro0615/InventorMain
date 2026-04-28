<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cotización #{{ $cotizacion->id }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #334155;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }
        .container { padding: 40px; }
        .header { border-bottom: 2px solid #2563eb; padding-bottom: 20px; margin-bottom: 30px; }
        .company-name { font-size: 24px; font-weight: bold; color: #1e40af; }
        .doc-title { font-size: 20px; color: #64748b; text-align: right; text-transform: uppercase; }
        
        .info-grid { width: 100%; margin-bottom: 30px; }
        .info-box { vertical-align: top; width: 50%; }
        .info-label { font-weight: bold; color: #1e40af; text-transform: uppercase; font-size: 10px; margin-bottom: 5px; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th { background-color: #f8fafc; padding: 12px 8px; text-align: left; border-bottom: 2px solid #e2e8f0; color: #1e40af; font-size: 11px; }
        td { padding: 12px 8px; border-bottom: 1px solid #e2e8f0; }
        
        .totals { float: right; width: 250px; }
        .total-row { padding: 8px 0; border-bottom: 1px solid #e2e8f0; }
        .total-label { font-weight: bold; }
        .total-value { text-align: right; font-weight: bold; }
        .grand-total { font-size: 16px; color: #2563eb; border-bottom: 2px solid #2563eb; }
        
        .footer { position: fixed; bottom: 40px; left: 40px; right: 40px; border-top: 1px solid #e2e8f0; padding-top: 10px; text-align: center; color: #94a3b8; font-size: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <table width="100%">
                <tr>
                    <td style="border:none; padding:0;">
                        <div class="company-name">{{ optional($user->negocio)->nombre ?? 'MI NEGOCIO' }}</div>
                        <div style="margin-top:5px;">NIT: {{ $user->nit ?? 'N/A' }}</div>
                        <div>{{ optional($user->negocio)->direccion ?? '' }}</div>
                        <div>Tel: {{ $user->telefono ?? '' }}</div>
                    </td>
                    <td style="border:none; padding:0; text-align:right;">
                        <div class="doc-title">Cotización Comercial</div>
                        <div style="font-size:16px; font-weight:bold; color:#2563eb; margin-top:5px;">#{{ str_pad($cotizacion->id, 5, '0', STR_PAD_LEFT) }}</div>
                        <div style="margin-top:5px;">Fecha: {{ \Carbon\Carbon::parse($cotizacion->fecha)->format('d/m/Y') }}</div>
                    </td>
                </tr>
            </table>
        </div>

        <table class="info-grid">
            <tr>
                <td class="info-box" style="border:none;">
                    <div class="info-label">Cotizado a:</div>
                    <div style="font-size:14px; font-weight:bold;">{{ optional($cotizacion->cliente)->nombre ?? 'Cliente N/A' }}</div>
                    <div>NIT/CC: {{ optional($cotizacion->cliente)->nit ?? 'N/A' }}</div>
                    <div>Teléfono: {{ optional($cotizacion->cliente)->telefono ?? 'N/A' }}</div>
                    <div>Email: {{ optional($cotizacion->cliente)->email ?? 'N/A' }}</div>
                </td>
                <td class="info-box" style="border:none; text-align:right;">
                    <div class="info-label">Validez de la oferta:</div>
                    <div>Esta cotización es válida por 15 días calendario</div>
                    <div style="margin-top:10px;" class="info-label">Vendedor:</div>
                    <div>{{ $user->name ?? 'Vendedor' }}</div>
                </td>
            </tr>
        </table>

        <table>
            <thead>
                <tr>
                    <th>Descripción del Producto</th>
                    <th style="text-align:center;">Cant.</th>
                    <th style="text-align:right;">Precio Unit.</th>
                    <th style="text-align:right;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cotizacion->detalles as $detalle)
                <tr>
                    <td>{{ optional($detalle->producto)->nombre ?? 'Producto eliminado' }}</td>
                    <td style="text-align:center;">{{ $detalle->cantidad }}</td>
                    <td style="text-align:right;">$ {{ number_format($detalle->precio_unitario, 0, ',', '.') }}</td>
                    <td style="text-align:right;">$ {{ number_format($detalle->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="totals">
            <div class="total-row">
                <table width="100%">
                    <tr>
                        <td style="border:none; padding:0;" class="total-label">SUBTOTAL</td>
                        <td style="border:none; padding:0;" class="total-value">$ {{ number_format($cotizacion->subtotal, 0, ',', '.') }}</td>
                    </tr>
                </table>
            </div>
            <div class="total-row">
                <table width="100%">
                    <tr>
                        <td style="border:none; padding:0;" class="total-label">IMPUESTOS (0%)</td>
                        <td style="border:none; padding:0;" class="total-value">$ {{ number_format($cotizacion->impuestos, 0, ',', '.') }}</td>
                    </tr>
                </table>
            </div>
            <div class="total-row grand-total">
                <table width="100%">
                    <tr>
                        <td style="border:none; padding:0; color:#2563eb;" class="total-label">TOTAL COTIZADO</td>
                        <td style="border:none; padding:0; color:#2563eb;" class="total-value">$ {{ number_format($cotizacion->total, 0, ',', '.') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div style="clear:both;"></div>

        <div style="margin-top:50px; padding:15px; background-color:#f8fafc; border-radius:8px; font-size:11px;">
            <strong>Términos y Condiciones:</strong>
            <ul style="margin-top:5px; padding-left:20px;">
                <li>Precios sujetos a cambios sin previo aviso.</li>
                <li>La disponibilidad de los productos está sujeta al inventario al momento de la compra.</li>
                <li>Esta cotización no constituye una factura de venta ni obligación tributaria.</li>
            </ul>
        </div>

        <div class="footer">
            <p>Generado por el Sistema de Gestión de Inventario Pro el {{ now()->format('d/m/Y H:i') }}</p>
            <p>Gracias por confiar en nosotros.</p>
        </div>
    </div>
</body>
</html>
