<div class="card">
    <div style="display:flex; justify-content:space-between; align-items:center;">
        <h2 class="card-title">Historial de ventas</h2>
    </div>

    {{-- FILTRO POR FECHA --}}
    <form method="GET" style="margin:1rem 0; display:flex; gap:1rem;">
        <input type="date" name="fecha" value="{{ $fecha }}">
        <button class="btn btn-print">Buscar</button>

        <a href="{{ route('historial.index') }}" class="btn btn-delete"></a>
            Limpiar
        </a>
    </form>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Producto</th>
                    <th class="text-right">Cantidad vendida</th>
                    <th class="text-right">Total vendido</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($historial as $item)
                    <tr>
                        <td>{{ $item->producto }}</td>
                        <td class="text-right">{{ $item->total_cantidad }}</td>
                        <td class="text-right">
                            $ {{ number_format($item->total_vendido, 0, ',', '.') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center">
                            No hay ventas en esta fecha
                        </td>
                    </tr>
                @endforelse
            </tbody>

            @if ($historial->count())
                <tfoot>
                    <tr>
                        <th>Total del día</th>
                        <th></th>
                        <th class="text-right">
                            $ {{ number_format($totalDia, 0, ',', '.') }}
                        </th>
                    </tr>
                </tfoot>
            @endif
        </table>
    </div>
</div>
