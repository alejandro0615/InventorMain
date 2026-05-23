<div id="tourt-historial" class="card">
    <div style="display:flex; justify-content:space-between; align-items:center;">
        <h2 class="card-title">Historial de ventas</h2>
    </div>

    <style>
        .historial-table-wrapper {
            border-radius: 1rem;
            overflow: hidden;
            border: 1px solid var(--border-color);
            background: var(--bg-card);
            box-shadow: var(--card-shadow);
        }

        .historial-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            min-width: 0;
            background: transparent;
        }

        .historial-table thead th {
            background: transparent;
        }

        .historial-table th,
        .historial-table td {
            white-space: nowrap;
        }

        @media (max-width: 768px) {
            #tourt-filtros {
                flex-wrap: wrap;
            }

            .historial-table-wrapper {
                overflow: visible;
            }

            .historial-table thead {
                display: none;
            }

            .historial-table tbody,
            .historial-table tr,
            .historial-table td,
            .historial-table tfoot,
            .historial-table th {
                display: block;
                width: 100%;
            }

            .historial-table tbody tr,
            .historial-table tfoot tr {
                margin-bottom: 1rem;
                border: 1px solid var(--border-color);
                border-radius: 1rem;
                overflow: hidden;
                box-shadow: 0 12px 28px rgba(0, 0, 0, 0.25);
                background: linear-gradient(180deg, rgba(15, 23, 42, 0.98), rgba(30, 41, 59, 0.98));
            }

            .historial-table td,
            .historial-table tfoot th {
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
                gap: 1rem;
                padding: 0.9rem 1rem;
                border-bottom: 1px solid rgba(148, 163, 184, 0.16);
                white-space: normal;
                color: #f8fafc;
                font-size: 0.84rem;
            }

            .historial-table tr,
            .historial-table td { background: transparent; }

            .historial-table td:last-child,
            .historial-table tfoot th:last-child {
                border-bottom: none;
            }

            .historial-table td::before,
            .historial-table tfoot th::before {
                content: attr(data-label);
                flex: 0 0 40%;
                font-size: 0.69rem;
                font-weight: 800;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                color: #cbd5e1;
                padding-top: 0.08rem;
            }

            .historial-table td > *,
            .historial-table tfoot th > * {
                color: #f8fafc;
                text-align: right;
            }

            /* Botones pequeños y consistentes (texto) */
            .historial-table td[data-label="Acciones"] a,
            .historial-table td[data-label="Acciones"] button {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                padding: 0.35rem 0.7rem;
                font-size: 0.75rem;
                font-weight: 800;
                border-radius: 999px;
                min-width: 56px;
                text-decoration: none;
            }

            .historial-table td[data-label="Acciones"] a,
            .historial-table td[data-label="Acciones"] button {
                position: relative;
                z-index: 8;
            }

            .historial-table td[data-label="Acciones"] {
                display: flex;
                flex-direction: column;
                align-items: stretch;
                gap: 0.5rem;
                min-height: 62px;
            }

            .historial-table td[data-label="Acciones"]::before { order: 0; width: 100%; flex: none; }

            .historial-table td[data-label="Acciones"] .hist-actions {
                order: 1;
                margin-top: auto;
                display: flex;
                justify-content: flex-end;
                gap: 0.5rem;
                align-self: flex-end;
                flex-wrap: wrap;
            }

            .historial-table tfoot th {
                font-weight: 800;
            }
        }
    </style>

    {{-- FILTRO POR FECHA --}}
    <form id="tourt-filtros" method="GET" style="margin:1rem 0; display:flex; gap:1rem;">
        <input type="date" name="fecha" value="{{ $fecha }}">
        <button class="btn btn-print">Buscar</button>
        <a href="{{ route('historial.index') }}" class="btn btn-delete">Limpiar</a>
    </form>
    <div id="tourt-tablaH" class="table-wrapper historial-table-wrapper">
        <table class="historial-table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th class="text-right">Cantidad vendida: </th>
                    <th class="text-right">Total vendido: </th>
                </tr>
            </thead>

            <tbody>
                @forelse ($historial as $item)
                    <tr>
                        <td data-label="Producto">{{ $item->producto }}</td>
                        <td data-label="Cantidad vendida" class="text-right">{{ $item->total_cantidad }}</td>
                        <td data-label="Total vendido" class="text-right">
                            $ {{ number_format($item->total_vendido, 0, ',', '.') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center text-muted">
                            No hay ventas en esta fecha
                        </td>
                    </tr>
                @endforelse
            </tbody>

            @if ($historial->count())
                <tfoot>
                    <tr>
                        <th data-label="Total del día">Total del día</th>
                        <th data-label=""> </th>
                        <th data-label="Total" class="text-right">
                            $ {{ number_format($totalDia, 0, ',', '.') }}
                        </th>
                    </tr>
                </tfoot>
            @endif
        </table>
    </div>

    <div style="margin-top: 1.5rem;">
        {{ $historial->links() }}
    </div>
</div>
