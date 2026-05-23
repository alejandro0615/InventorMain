<div class="card">
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

        .crud-actions {
            display: inline-flex;
            gap: 0.4rem;
        }

        .crud-action-btn {
            padding: 0.35rem 0.7rem;
            border-radius: 0.4rem;
            font-weight: 800;
            font-size: 0.7rem;
            color: white;
            text-decoration: none;
            background: var(--primary);
            text-transform: uppercase;
        }

        .providers-table-wrapper {
            border-radius: 1rem;
            overflow: hidden;
            border: 1px solid var(--border-color);
            background: var(--bg-card);
            box-shadow: var(--card-shadow);
        }

        .providers-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .providers-table thead th {
            background: transparent;
            white-space: nowrap;
        }

        .providers-table th,
        .providers-table td {
            white-space: nowrap;
        }

        @media (max-width: 768px) {
            .page-header {
                margin-bottom: 1rem;
                padding-bottom: 0.75rem;
            }

            .page-actions {
                margin-bottom: 1rem !important;
            }

            .providers-table-wrapper {
                overflow: visible;
                -webkit-overflow-scrolling: touch;
            }

            .providers-table {
                min-width: 0;
                background: transparent;
            }

            .providers-table tr,
            .providers-table td { background: transparent; }

            .providers-table th,
            .providers-table td {
                padding: 0.8rem 0.85rem;
                font-size: 0.82rem;
            }

            .crud-actions {
                gap: 0.45rem;
                flex-wrap: wrap;
                justify-content: flex-end;
            }

            .crud-action-btn {
                padding: 0.42rem 0.8rem;
                font-size: 0.68rem;
                white-space: nowrap;
                min-width: 82px;
                border-radius: 999px;
                box-shadow: 0 4px 10px rgba(37, 99, 235, 0.12);
            }
            /* Convertir filas en tarjetas */
            .providers-table thead { display: none; }
            .providers-table tbody,
            .providers-table tr,
            .providers-table td { display: block; width: 100%; }

            .providers-table tbody tr {
                margin-bottom: 1rem;
                border: 1px solid var(--border-color);
                border-radius: 1rem;
                overflow: hidden;
                box-shadow: 0 12px 28px rgba(0, 0, 0, 0.25);
                background: linear-gradient(180deg, rgba(15, 23, 42, 0.98), rgba(30, 41, 59, 0.98));
            }

            .providers-table td {
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
                gap: 1rem;
                padding: 0.9rem 1rem;
                font-size: 0.84rem;
                border-bottom: 1px solid rgba(148, 163, 184, 0.16);
                white-space: normal;
            }

            .providers-table td:last-child { border-bottom: none; }

            .providers-table td::before {
                content: attr(data-label);
                flex: 0 0 42%;
                font-size: 0.69rem;
                font-weight: 800;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                color: #cbd5e1;
                padding-top: 0.08rem;
            }

            .providers-table td,
            .providers-table td > * { color: #f8fafc; }

            .providers-table td > * { text-align: right; }

            .providers-table td[data-label="Acciones"] {
                flex-direction: row;
                align-items: center;
                gap: 0.5rem;
            }

            .providers-table td[data-label="Acciones"]::before {
                width: 100%;
                flex: none;
            }

            .providers-table td[data-label="Acciones"] .crud-actions {
                width: auto;
                flex-direction: row;
                justify-content: flex-end;
                gap: 0.5rem;
                align-items: center;
                margin-left: -72px;
            }

            .providers-table td[data-label="Acciones"] .crud-action-btn {
                box-shadow: none;
                min-width: 56px;
                padding: 0.35rem 0.7rem;
            }

            /* Acciones: etiqueta arriba, botones abajo (móvil) */
            .providers-table td[data-label="Acciones"] {
                display: flex;
                flex-direction: column;
                align-items: stretch;
                gap: 0.5rem;
                min-height: 62px;
            }

            .providers-table td[data-label="Acciones"]::before { order: 0; width: 100%; flex: none; }

            .providers-table td[data-label="Acciones"] .crud-actions {
                order: 1;
                margin-top: auto;
                justify-content: flex-end;
                display: flex;
                gap: 0.5rem;
                align-self: flex-end;
                flex-wrap: wrap;
            }

            .providers-table td[data-label="Acciones"] a,
            .providers-table td[data-label="Acciones"] button {
                position: relative;
                z-index: 8;
            }

            .providers-table td[data-label="Acciones"] .crud-action-btn[style] {
                background: rgb(233, 15, 15) !important;
            }

            /* Evitar que botones queden recortados y mejorar visibilidad */
            .providers-table td[data-label="Acciones"] a,
            .providers-table td[data-label="Acciones"] button {
                position: relative;
                z-index: 8;
            }

            .providers-table td[data-label="Acciones"] .crud-action-btn[style] {
                background: rgba(239,68,68,0.12) !important;
            }

            /* Botones pequeños y consistentes (texto) */
            .providers-table td[data-label="Acciones"] a,
            .providers-table td[data-label="Acciones"] button {
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
        }
    </style>

    {{-- Encabezado estándar --}}
    <div id= "tourt-directorio"class="page-header">
        <h2 class="card-title" style="font-size: 1.5rem; margin: 0; text-transform: uppercase;">Catálogo de terceros</h2>
        <p class="text-muted" style="font-size: 0.8rem;">Administración de contactos y entidades comerciales.</p>
    </div>

    {{-- BARRA DE BÚSQUEDA --}}
    <div id="tour-search-proveedores" style="margin-bottom: 1.5rem;">
        <form method="GET" action="{{ route('proveedores.index') }}">
            <input type="text" name="q" placeholder="Buscar por nombre, NIT o razón social..." value="{{ $search ?? '' }}"
                   style="width: 100%; padding: 0.8rem 1.2rem; border-radius: 0.75rem; border: 2px solid var(--border-color); background: var(--bg-body); color: var(--text-main); font-size: 0.95rem;">
        </form>
    </div>

    <div class="page-actions" style="margin-bottom: 1.5rem; margin-top: 0;">
        <div id="tour-nuevo-proveedor">
            <a href="{{ route('proveedores.create') }}" class="btn-primary-action">
                Registrar nuevo tercero
            </a>
        </div>
    </div>

    <div id="tourt-tableProve" class="table-wrapper providers-table-wrapper">
        <table class="providers-table">
            <thead>
                <tr>
                    <th>Razón Social / Nombre</th>
                    <th>NIT / Identificación</th>
                    <th>Teléfono de Contacto</th>
                    <th class="text-right">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($proveedores as $proveedor)
                    <tr>
                        <td class="bold" data-label="Razón Social / Nombre">{{ $proveedor->nombre }}</td>
                        <td data-label="NIT / Identificación">{{ $proveedor->nit }}</td>
                        <td data-label="Teléfono de Contacto">{{ $proveedor->telefono }}</td>
                        <td class="text-right" data-label="Acciones">
                            <div class="crud-actions">
                                <a href="{{ route('proveedores.edit', $proveedor) }}" class="crud-action-btn">Editar</a>
                                <form action="{{ route('proveedores.destroy', $proveedor) }}" method="POST" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="crud-action-btn" style="background:#ef4444;" onclick="return confirm('¿Confirmar eliminación?')">Borrar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">No se encontraron terceros registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 1.5rem;">
        {{ $proveedores->links() }}
    </div>
</div>
