<div class="card">
    @php $etiquetaDestino = 'DIAN'; @endphp
    <style>
        .page-header {
            margin-bottom: 1.5rem;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 1rem;
        }
        
        .page-actions {
            display: flex;
            justify-content: flex-start;
            margin-top: 1rem;
        }

        .movimientos-actions {
            display: flex;
            flex-wrap: nowrap;
            align-items: center;
            gap: 0.3rem;
        }

        .btn-compact {
            padding: 0.35rem 0.6rem !important;
            font-size: 0.75rem !important;
            border-radius: 0.4rem !important;
            font-weight: 800;
            text-transform: uppercase;
        }

        .btn-factus { background: #1e293b; color: #fff; }
        .btn-pdf-c { background: #ef4444; color: #fff; }
        .btn-download-c { background: #10b981; color: #fff; }
        .btn-print-c { background: #3b82f6; color: #fff; }
        .btn-delete-c { background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.1); }

        .movimientos-status-pill {
            font-size: 0.7rem;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-weight: 800;
        }

        /* FILTROS MEJORADOS PARA DARK MODE */
        .filter-container {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            margin-bottom: 1.5rem;
            background: var(--bg-body); /* Usar fondo de cuerpo para contraste */
            padding: 1.25rem;
            border-radius: 1rem;
            border: 1px solid var(--border-color);
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.05);
        }

        .filter-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-size: 0.7rem;
            font-weight: 800;
            text-transform: uppercase;
            color: var(--primary); /* Título en azul para que resalte */
            letter-spacing: 0.05em;
        }

        .filter-input {
            width: 100%;
            background-color: var(--bg-card) !important;
            color: var(--text-main) !important;
            border: 1px solid var(--border-color) !important;
            padding: 0.6rem !important;
            font-size: 0.9rem !important;
            border-radius: 0.6rem !important;
        }

        .filter-input:focus {
            border-color: var(--primary) !important;
            outline: none;
        }
    </style>
    
    <div class="page-header">
        <h2 class="card-title" style="font-size: 1.6rem; margin: 0; text-transform: uppercase;">Registro de Salidas</h2>
        <p class="text-muted" style="font-size: 0.85rem;">Historial de movimientos y facturación electrónica.</p>
        
        <div class="page-actions">
            <a href="{{ route('movimientos.create') }}" class="btn-primary-action">
                Nuevo Movimiento
            </a>
        </div>
    </div>

    {{-- CONTENEDOR DE FILTROS CON MEJOR CONTRASTE --}}
    <form method="GET" action="{{ route('movimientos.index') }}" class="filter-container">
        <div class="filter-group" style="flex: 2; min-width: 220px;">
            <label>Filtrar por Cliente</label>
            <select name="proveedor_id" class="filter-input">
                <option value="">— Ver todos los registros —</option>
                @foreach ($proveedores as $proveedor)
                    <option value="{{ $proveedor->id }}" {{ $proveedorId == $proveedor->id ? 'selected' : '' }}>
                        {{ $proveedor->nombre }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="filter-group" style="flex: 1; min-width: 150px;">
            <label>Fecha de Registro</label>
            <input type="date" name="fecha" value="{{ $fecha }}" class="filter-input">
        </div>
        <div style="display:flex; align-items:flex-end; gap:.5rem;">
            <button class="btn-download" style="padding: 0.7rem 1.5rem;">Filtrar</button>
            <a href="{{ route('movimientos.index') }}" class="btn-delete" style="background: var(--text-muted); color: #fff; padding: 0.7rem 1.5rem;">Resetear</a>
        </div>
    </form>

    <div class="table-wrapper">
        <table class="min-w-full">
            <thead>
                <tr>
                    <th>Resumen</th>
                    <th>Cliente</th>
                    <th>Estado</th>
                    <th class="text-right">Total</th>
                    <th>Fecha</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($movimientos as $salida)
                    <tr>
                        <td class="bold">{{ $salida->detalles->count() }} ítems</td>
                        <td>{{ $salida->cliente->nombre }}</td>
                        <td>
                            @if ($salida->estado_dian === 'validada')
                                <span class="movimientos-status-pill" style="background:rgba(16, 185, 129, 0.15); color:#10b981;">VALIDADA</span>
                            @elseif ($salida->estado_dian === 'pendiente')
                                <span class="movimientos-status-pill" style="background:rgba(245, 158, 11, 0.15); color:#f59e0b;">PROCESO</span>
                            @else
                                <span class="movimientos-status-pill" style="background:rgba(239, 68, 68, 0.15); color:#ef4444;">ERROR</span>
                            @endif
                        </td>
                        <td class="text-right bold" style="color: var(--primary); font-size: 1.1rem;">$ {{ number_format($salida->total, 0, ',', '.') }}</td>
                        <td>{{ \Carbon\Carbon::parse($salida->fecha)->format('d/m/Y') }}</td>
                        <td style="width: 1%;">
                            <div class="movimientos-actions">
                                @if ($salida->url_factus)
                                    <a href="{{ $salida->url_factus }}" target="_blank" class="btn-compact btn-factus" title="Ver en Factus">DIAN</a>
                                @endif
                                <a href="{{ route('salidas.factura', $salida->id) }}" target="_blank" class="btn-compact btn-pdf-c" title="Ver PDF">PDF</a>
                                <a href="{{ route('salidas.factura.download', $salida->id) }}" class="btn-compact btn-download-c" title="Descargar">DESC</a>
                                <a href="{{ route('salidas.factura', $salida->id) }}" target="_blank" class="btn-compact btn-print-c" title="Imprimir">IMP</a>
                                <form action="{{ route('salidas.destroy', $salida->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button class="btn-compact btn-delete-c" onclick="return confirm('¿Confirmar?')">X</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted">Sin registros encontrados.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
