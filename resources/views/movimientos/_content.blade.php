<h2 class="card-title">Movimientos</h2>

@if (session('success'))
    <div style="color:#166534; font-size:0.9rem; margin-bottom:0.75rem;">
        {{ session('success') }}
    </div>
@endif

<form method="GET" action="{{ route('movimientos.index') }}" style="margin-bottom:1rem; display:flex; gap:0.5rem;">
    <input type="text" name="q" placeholder="Buscar por producto o proveedor" value="{{ $search }}"
        style="flex:1; padding:0.4rem 0.6rem; border-radius:0.5rem; border:1px solid #d1d5db;">
    <button type="submit" class="btn-tour" style="background:#2563eb;">
        Buscar
    </button>
    <a href="{{ route('movimientos.create') }}" class="btn-tour"
        style="background:#16a34a; text-decoration:none; padding:0.4rem 0.8rem;">
        Nuevo Movimiento
    </a>
    @if ($search)
        <a href="{{ route('movimientos.index') }}" class="btn-tour"
            style="background:#1645a4; text-decoration:none; padding:0.4rem 0.8rem;">
            Limpiar
        </a>
    @endif
</form>

<div class="table-responsive">
    <table style="width:100%; border-collapse:collapse; font-size:0.9rem;">
        <thead>
            <tr>
                <th style="border-bottom:1px solid #e5e7eb; padding:0.5rem; text-align:left;">Producto</th>
                <th style="border-bottom:1px solid #e5e7eb; padding:0.5rem; text-align:left;">Proveedor</th>
                <th style="border-bottom:1px solid #e5e7eb; padding:0.5rem; text-align:left;">Tipo</th>
                <th style="border-bottom:1px solid #e5e7eb; padding:0.5rem; text-align:right;">Cantidad</th>
                <th style="border-bottom:1px solid #e5e7eb; padding:0.5rem; text-align:left;">Fecha</th>
                <th style="border-bottom:1px solid #e5e7eb; padding:0.5rem;">Factura</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($movimientos as $producto)
                @foreach ($producto->proveedores as $proveedor)
                    <tr>
                        <td style="border-bottom:1px solid #f3f4f6; padding:0.5rem;">
                            {{ $producto->nombre }}
                        </td>
                        <td style="border-bottom:1px solid #f3f4f6; padding:0.5rem;">
                            {{ $proveedor->nombre }}
                        </td>
                        <td style="border-bottom:1px solid #f3f4f6; padding:0.5rem;">
                            {{ $proveedor->pivot->tipo_movimiento ?? ($proveedor->pivot->tipo ?? '-') }}
                        </td>
                        <td style="border-bottom:1px solid #f3f4f6; padding:0.5rem; text-align:right;">
                            {{ $proveedor->pivot->cantidad }}
                        </td>
                        <td style="border-bottom:1px solid #f3f4f6; padding:0.5rem;">
                            {{ \Carbon\Carbon::parse($proveedor->pivot->fecha)->format('Y-m-d') }}
                        </td>
                        <td style="border-bottom:1px solid #f3f4f6; padding:0.5rem; text-align:center;">
                            @php
                                $salidaId = $proveedor->pivot->salida_id ?? null;
                            @endphp

                            @if ($salidaId)
                                <span style="font-size:0.75rem; color:#16a34a;">
                                    Factura #{{ $salidaId }}
                                </span>
                            @else
                                <span style="font-size:0.75rem; color:#9ca3af;">
                                    Sin factura
                                </span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            @empty
                <tr>
                    <td colspan="6" style="padding:0.75rem; text-align:center; color:#6b7280;">
                        No hay movimientos registrados.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @php
        $salidasUnicas = collect();

        foreach ($movimientos as $producto) {
            foreach ($producto->proveedores as $proveedor) {
                if (!empty($proveedor->pivot->salida_id)) {
                    $salidasUnicas->push($proveedor->pivot->salida_id);
                }
            }
        }

        $salidasUnicas = $salidasUnicas->unique()->values();
    @endphp

    @if ($salidasUnicas->count())
        <div style="margin-top:1rem; padding-top:0.5rem; border-top:1px solid #e5e7eb;">
            <h4 style="font-size:0.9rem; margin-bottom:0.5rem;">Facturas generadas</h4>

            @foreach ($salidasUnicas as $salidaId)
                <div style="margin-bottom:0.4rem;">
                    <span style="margin-right:0.5rem;">Factura #{{ $salidaId }}</span>

                    <a href="{{ route('salidas.factura', $salidaId) }}" target="_blank" class="btn-pdf">
                        <i class="fas fa-file-pdf"></i> Ver PDF
                    </a>

                    <a href="{{ route('salidas.factura.download', $salidaId) }}" class="btn-download">
                        <i class="fas fa-download"></i> Descargar
                    </a>

                    <button onclick="imprimirFactura({{ $salidaId }})" class="btn-print">
                        <i class="fas fa-print"></i> Imprimir
                    </button>

                    <form action="{{ route('salidas.destroy', $salidaId) }}" method="POST"
                        style="display:inline-block;"
                        onsubmit="return confirm('¿Seguro que deseas eliminar esta factura?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-delete">
                            <i class="fas fa-trash"></i> Eliminar
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
    @endif
</div>
