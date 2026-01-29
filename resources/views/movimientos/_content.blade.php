<h2 class="card-title">Movimientos</h2>

@if (session('success'))
    <div style="color:#166534; font-size:0.9rem; margin-bottom:0.75rem;">
        {{ session('success') }}
    </div>
@endif

<form method="GET" action="{{ route('movimientos.index') }}" style="margin-bottom:1rem; display:flex; gap:0.5rem;">

    <select name="proveedor_id">
        <option value="">-- Proveedor --</option>
        @foreach ($proveedores as $prov)
            <option value="{{ $prov->id }}" {{ $proveedorId == $prov->id ? 'selected' : '' }}>
                {{ $prov->nombre }}
            </option>
        @endforeach
    </select>

    <input type="date" name="fecha" value="{{ $fecha }}">

    <button class="btn-tour">Buscar</button>
</form>

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
                <th style="border-bottom:1px solid #e5e7eb; padding:0.5rem; text-align:left;">Proveedor / Cliente</th>
                <th style="border-bottom:1px solid #e5e7eb; padding:0.5rem; text-align:left;">Tipo</th>
                <th style="border-bottom:1px solid #e5e7eb; padding:0.5rem; text-align:right;">Cantidad</th>
                <th style="border-bottom:1px solid #e5e7eb; padding:0.5rem; text-align:left;">Fecha</th>
                <th style="border-bottom:1px solid #e5e7eb; padding:0.5rem; text-align:center;">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($movimientos as $salida)
                <tr>
                    <td style="border-bottom:1px solid #f3f4f6; padding:0.5rem;">
                        {{ $salida->detalles->count() }}
                        {{ $salida->detalles->count() === 1 ? 'producto' : 'productos' }}
                    </td>

                    <td style="border-bottom:1px solid #f3f4f6; padding:0.5rem;">
                        {{ $salida->cliente->nombre }}
                    </td>

                    <td style="border-bottom:1px solid #f3f4f6; padding:0.5rem;">
                        salida
                    </td>

                    <td style="border-bottom:1px solid #f3f4f6; padding:0.5rem; text-align:right;">
                        {{ $salida->detalles->sum('cantidad') }}
                    </td>

                    <td style="border-bottom:1px solid #f3f4f6; padding:0.5rem;">
                        {{ \Carbon\Carbon::parse($salida->fecha)->format('Y-m-d') }}
                    </td>

                    <td style="border-bottom:1px solid #f3f4f6; padding:0.5rem; text-align:center;">
                        
                        <a href="{{ route('salidas.factura', $salida->id) }}" target="_blank" class="btn-pdf">
                            <i class="fas fa-file-pdf"></i> Ver PDF
                        </a>

                        <a href="{{ route('salidas.factura.download', $salida->id) }}" class="btn-download">
                            <i class="fas fa-download"></i> Descargar
                        </a>

                        <form action="{{ route('salidas.destroy', $salida->id) }}" method="POST"
                            style="display:inline-block;"
                            onsubmit="return confirm('¿Seguro que deseas eliminar esta factura?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-delete">
                                <i class="fas fa-trash"></i> Eliminar
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="padding:0.75rem; text-align:center; color:#6b7280;">
                        No hay movimientos registrados.
                    </td>
                </tr>
            @endforelse
        </tbody>

    </table>
</div>
