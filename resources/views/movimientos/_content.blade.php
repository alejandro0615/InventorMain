<div class="table-responsive">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
        <h2 class="card-title">Movimientos de inventario</h2>

        <a href="{{ route('movimientos.create') }}" class="btn btn-print">
            + Nuevo movimiento
        </a>
    </div>
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
    <h2 class="card-title">Movimientos de inventario</h2>

    <a href="{{ route('movimientos.create') }}" class="btn btn-print">
        + Nuevo movimiento
    </a>
</div>
    <table class="tabla-movimientos">
        <thead>
            <tr>
                <th>Productos</th>
                <th>Proveedor</th>
                <th>Tipo</th>
                <th class="text-right">Cantidad</th>
                <th>Fecha</th>
                <th class="text-center">Acciones</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($movimientos as $salida)
                <tr>
                    <td>
                        {{ $salida->detalles->count() }}
                        {{ $salida->detalles->count() === 1 ? 'producto' : 'productos' }}
                    </td>

                    <td>{{ $salida->cliente->nombre }}</td>

                    <td>
                        <span class="badge badge-salida">Salida</span>
                    </td>

                    <td class="text-right">
                        {{ $salida->detalles->sum('cantidad') }}
                    </td>

                    <td>{{ \Carbon\Carbon::parse($salida->fecha)->format('Y-m-d') }}</td>

                    <td class="acciones">
                        <a href="{{ route('salidas.factura', $salida->id) }}" target="_blank" class="btn btn-pdf">
                            VER PDF
                        </a>

                        <a href="{{ route('salidas.factura.download', $salida->id) }}" class="btn btn-download">
                            DESCARGAR
                        </a>

                        <form action="{{ route('salidas.destroy', $salida->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-delete" onclick="return confirm('¿Eliminar factura?')">
                                ELIMINAR
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">
                        No hay movimientos registrados.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
