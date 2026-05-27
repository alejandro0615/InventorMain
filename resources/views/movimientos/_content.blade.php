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
            justify-content: center;
            /* centrar por defecto */
        }

        /* Evitar desplazamientos negativos heredados de otras tablas */
        .actions-cell .movimientos-actions {
            margin-left: 0 !important;
        }

        .btn-compact {
            padding: 0.35rem 0.6rem !important;
            font-size: 0.75rem !important;
            border-radius: 0.4rem !important;
            font-weight: 800;
            text-transform: uppercase;
        }

        /* Estilos base para que los botones se vean como en productos */
        .btn-compact {
            box-shadow: 0 4px 10px rgba(37, 99, 235, 0.12);
            min-width: 82px;
            border-radius: 0.4rem;
        }

        .btn-factus {
            background: var(--btn-secondary-bg);
            color: var(--btn-text-contrast);
        }

        .btn-pdf-c {
            background: var(--btn-danger-bg);
            color: var(--btn-text-contrast);
        }

        .btn-download-c {
            background: var(--btn-success-bg);
            color: var(--btn-text-contrast);
        }

        .btn-print-c {
            background: var(--primary);
            color: var(--btn-text-contrast);
        }

        .btn-delete-c {
            background: transparent;
            color: var(--btn-danger-bg);
            border: 1px solid var(--border-color);
        }

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
            background: var(--bg-body);
            /* Usar fondo de cuerpo para contraste */
            padding: 1.25rem;

            .movimientos-table td>* {
                text-align: right;
                color: var(--text-main);
            }

            .filter-group label {
                display: block;
                margin-bottom: 0.5rem;
                font-size: 0.7rem;
                font-weight: 800;
                text-transform: uppercase;
                color: var(--primary);
                /* Título en azul para que resalte */
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

            .movimientos-table-wrapper {
                border-radius: 1rem;
                overflow: hidden;
                border: 1px solid var(--border-color);
                background: var(--bg-card);
                box-shadow: var(--card-shadow);
                max-width: 1100px;
                /* limitar ancho de la tabla */
                margin: 0 auto;
                /* centrar en su contenedor */
                padding: 0 0.75rem;
                /* pequeño acolchado lateral */
                box-sizing: border-box;
            }

            .movimientos-table {
                width: 100%;
                border-collapse: separate;
                border-spacing: 0;
                table-layout: fixed;
                /* permitir anchos fijos y wrap */
            }

            .movimientos-table thead th {
                background: transparent;
                white-space: nowrap;
            }

            .movimientos-table th,
            .movimientos-table td {
                white-space: normal;
                /* permitir wrap para mejorar legibilidad */
                overflow-wrap: anywhere;
                word-break: break-word;
            }

            /* Asignar anchos relativos a columnas para evitar que la tabla crezca demasiado */
            .movimientos-table th:nth-child(1),
            .movimientos-table td:nth-child(1) {
                width: 8%;
            }

            .movimientos-table th:nth-child(2),
            .movimientos-table td:nth-child(2) {
                width: 40%;
            }

            .movimientos-table th:nth-child(3),
            .movimientos-table td:nth-child(3) {
                width: 18%;
            }

            .movimientos-table th:nth-child(4),
            .movimientos-table td:nth-child(4) {
                width: 10%;
            }

            .movimientos-table th:nth-child(5),
            .movimientos-table td:nth-child(5) {
                width: 10%;
            }

            .movimientos-table th:nth-child(6),
            .movimientos-table td:nth-child(6) {
                width: 9%;
            }

            .movimientos-table th:nth-child(7),
            .movimientos-table td:nth-child(7) {
                width: 15%;
            }

            .movimientos-actions {
                display: inline-flex;
                flex-wrap: wrap;
                align-items: center;
                gap: 0.35rem;
                justify-content: center;
                /* centrar acciones en la tabla */
            }

            .movimientos-mobile-card {
                display: none;
            }

            @media (max-width: 768px) {
                .page-header {
                    margin-bottom: 1rem;
                    padding-bottom: 0.75rem;
                }

                .filter-container {
                    padding: 1rem;
                    gap: 0.85rem;
                }

                .filter-group {
                    flex: 1 1 100% !important;
                    min-width: 0 !important;
                }

                .filter-container>div:last-child {
                    width: 100%;
                    justify-content: stretch;
                    flex-wrap: wrap;
                }

                .filter-container>div:last-child .btn-download,
                .filter-container>div:last-child .btn-delete {
                    flex: 1 1 140px;
                    text-align: center;
                }

                .movimientos-table-wrapper {
                    overflow: visible;
                    -webkit-overflow-scrolling: touch;
                }

                /* Forzar visibilidad en contenedores ancestros para evitar recorte de botones */
                .movimientos-table-wrapper,
                .movimientos-table tbody tr,
                .movimientos-table td[data-label="Acciones"],
                .movimientos-actions,
                .card {
                    overflow: visible !important;
                }

                .movimientos-table {
                    min-width: 0;
                }

                .movimientos-table thead {
                    display: none;
                }

                .movimientos-table tbody,
                .movimientos-table tr,
                .movimientos-table td {
                    display: block;
                    width: 100%;
                }

                .movimientos-table tbody tr {
                    margin-bottom: 1rem;
                    border: 1px solid var(--border-color);
                    border-radius: 1rem;
                    overflow: hidden;
                    /* mantener contenido dentro de la card */
                    box-shadow: 0 12px 28px rgba(0, 0, 0, 0.25);
                    background: linear-gradient(180deg, rgba(15, 23, 42, 0.98), rgba(30, 41, 59, 0.98));
                    position: relative;
                    /* para contener elementos relativos */
                    padding-bottom: 0.6rem;
                    /* espacio para acciones envueltas */
                    box-sizing: border-box;
                }

                .movimientos-table td {
                    display: flex;
                    justify-content: space-between;
                    align-items: flex-start;
                    gap: 1rem;
                    padding: 0.9rem 1rem;
                    font-size: 0.84rem;
                    border-bottom: 1px solid var(--border-color);
                    white-space: normal;
                }

                .movimientos-table td:last-child {
                    border-bottom: none;
                }

                .movimientos-table td::before {
                    content: attr(data-label);
                    flex: 0 0 40%;
                    font-size: 0.69rem;
                    font-weight: 800;
                    text-transform: uppercase;
                    letter-spacing: 0.05em;
                    color: var(--text-muted);
                    padding-top: 0.08rem;
                }

                .movimientos-table td>* {
                    text-align: right;
                    color: var(--text-main);
                }

                .movimientos-table td[data-label="Acciones"] {
                    align-items: center;
                }

                .movimientos-table td[data-label="Acciones"]::before {
                    align-self: center;
                }

                .movimientos-actions {
                    justify-content: flex-end;
                    /* alinear a la derecha dentro de la celda */
                    width: 100%;
                    gap: 0.45rem;
                    flex-wrap: wrap;
                    /* permitir varias filas si hay muchos botones */
                    overflow: hidden;
                    box-sizing: border-box;
                    padding-top: 0.25rem;
                }

                /* Limitar tamaño de botones y forzar 2 por fila (hasta 3 filas si hay muchos) */
                .movimientos-actions>* {
                     width: 100%;
                }

                /* Hacer que los botones llenen su celda para tener dos botones por fila */
                .movimientos-table td[data-label="Acciones"] a,
                .movimientos-table td[data-label="Acciones"] button,
                .movimientos-table td[data-label="Acciones"] .btn-compact {
                    width: 100% !important;
                    min-width: 0 !important;
                    box-sizing: border-box;
                }

                .btn-compact {
                    padding: 0.42rem 0.76rem !important;
                    font-size: 0.68rem !important;
                    white-space: nowrap;
                    border-radius: 999px !important;
                    box-shadow: 0 4px 10px rgba(37, 99, 235, 0.12);
                }

                .movimientos-status-pill {
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                }

                .movimientos-table td[data-label="Producto / Resumen"]>div {
                    text-align: right;
                }

                .movimientos-table td[data-label="Fecha"] {
                    white-space: nowrap;
                }

                .movimientos-table td[data-label="Acciones"] {
                    display: flex;
                    flex-direction: column;
                    /* apilar internamente si hace falta */
                    align-items: stretch;
                    /* ocupar ancho para contener botones */
                    justify-content: flex-end;
                    /* acciones al fondo de la card */
                    gap: 0.5rem;
                    min-height: 62px;
                    position: relative;
                    overflow: hidden !important;
                    /* forzar que no salgan fuera de la card */
                    padding-bottom: 0.4rem;
                    box-sizing: border-box;
                }

                /* Mejor centrado en escritorio: dar espacio mínimo a la columna de acciones */
                @media (min-width: 769px) {
                    .movimientos-table td[data-label="Acciones"] {
                        min-width: 220px;
                        /* ajustar según necesidad */
                        max-width: 320px;
                        padding-left: 0.5rem;
                        padding-right: 0.5rem;
                        box-sizing: border-box;
                    }

                    .movimientos-actions {
                        gap: 0.6rem;
                    }

                    .movimientos-table td[data-label="Acciones"] a,
                    .movimientos-table td[data-label="Acciones"] button {
                        margin: 0 !important;
                    }
                }

                .movimientos-table td[data-label="Acciones"]::before {
                    width: 100%;
                    flex: none;
                    order: 0;
                }

                .movimientos-table td[data-label="Acciones"] .movimientos-actions {
                    display: grid;
                    grid-template-columns: repeat(2, 1fr);
                    gap: 0.5rem;
                    width: 100%;
                }

                /* Botones pequeños y consistentes (texto) */
                .movimientos-table td[data-label="Acciones"] a,
                .movimientos-table td[data-label="Acciones"] button {
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

                /* Forzar estilos específicos de botones compactos dentro de la celda acciones */
                .movimientos-table td[data-label="Acciones"] a.btn-compact,
                .movimientos-table td[data-label="Acciones"] button.btn-compact,
                .movimientos-table td[data-label="Acciones"] a.btn-pdf-c,
                .movimientos-table td[data-label="Acciones"] a.btn-download-c,
                .movimientos-table td[data-label="Acciones"] a.btn-print-c,
                .movimientos-table td[data-label="Acciones"] a.btn-factus,
                .movimientos-table td[data-label="Acciones"] .btn-delete-c {
                    display: inline-flex !important;

                    padding: 0.35rem 0.7rem !important;
                    min-width: 56px !important;
                    height: auto !important;
                    border-radius: 999px !important;
                    box-shadow: none !important;
                    text-align: center !important;
                }

                .movimientos-table td[data-label="Acciones"] a,
                .movimientos-table td[data-label="Acciones"] button {
                    position: relative;
                    z-index: 8;
                }

                .movimientos-table td[data-label="Acciones"] .btn-delete-c {
                    background: rgb(227, 13, 13) !important;
                }
            }
    </style>

    <div id="tourt-M"class="page-header">
        <h2 class="card-title" style="font-size: 1.6rem; margin: 0; text-transform: uppercase;">Registro de Movimientos
        </h2>
        <p class="text-muted" style="font-size: 0.85rem;">Historial de movimientos y facturación electrónica.</p>

        <div class="page-actions">
            <a href="{{ route('movimientos.create') }}" class="btn-primary-action">
                Nuevo Movimiento
            </a>
        </div>
    </div>

    {{-- CONTENEDOR DE FILTROS CON MEJOR CONTRASTE --}}
    <form id="tour-filtros-movimientos" method="GET" action="{{ route('movimientos.index') }}"
        class="filter-container">
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
        <div id ="tourt-fecha"class="filter-group" style="flex: 1; min-width: 150px;">
            <label>Fecha de Registro</label>
            <input type="date" name="fecha" value="{{ $fecha }}" class="filter-input">
        </div>
        <div style="display:flex; align-items:flex-end; gap:.5rem;">
            <button class="btn-download" style="padding: 0.7rem 1.5rem;">Filtrar</button>
            <a href="{{ route('movimientos.index') }}" class="btn-delete" style="padding: 0.7rem 1.5rem;">Resetear</a>
        </div>
    </form>

    <div id="tour-tablaM" class="table-wrapper movimientos-table-wrapper">
        <table class="movimientos-table">
            <thead>
                <tr>
                    <th>Tipo</th>
                    <th>Producto / Resumen</th>
                    <th>Cliente / Proveedor</th>
                    <th>Estado DIAN</th>
                    <th class="text-right">Total</th>
                    <th>Fecha</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($movimientos as $mov)
                    @php
                        $esSalida = $mov->tipo_movimiento === 'salida';
                        $salida = $mov->salida;
                    @endphp
                    <tr>
                        <td data-label="Tipo">
                            @if ($esSalida)
                                <span class="movimientos-status-pill"
                                    style="background:rgba(245, 158, 11, 0.15); color:#f59e0b;">SALIDA</span>
                            @else
                                <span class="movimientos-status-pill"
                                    style="background:rgba(59, 130, 246, 0.15); color:#3b82f6;">ENTRADA</span>
                            @endif
                        </td>
                        <td class="bold" data-label="Producto / Resumen">
                            {{ $mov->producto->nombre }}
                            <div style="font-size: 0.7rem; font-weight: normal; color: var(--text-muted);">
                                Cantidad: {{ $mov->cantidad }} UND
                            </div>
                        </td>
                        <td data-label="Cliente / Proveedor">{{ $mov->proveedor->nombre }}</td>
                        <td data-label="Estado DIAN">
                            @if ($esSalida && $salida)
                                @if ($salida->estado_dian === 'validada')
                                    <span class="movimientos-status-pill"
                                        style="background:rgba(16, 185, 129, 0.15); color:#10b981;">VALIDADA</span>
                                @elseif ($salida->estado_dian === 'pendiente')
                                    <span class="movimientos-status-pill"
                                        style="background:rgba(245, 158, 11, 0.15); color:#f59e0b;">PROCESO</span>
                                @elseif ($salida->estado_dian === 'cancelada')
                                    <span class="movimientos-status-pill"
                                        style="background:rgba(148, 163, 184, 0.15); color:#64748b;">CANCELADA</span>
                                @else
                                    <span class="movimientos-status-pill"
                                        style="background:rgba(239, 68, 68, 0.15); color:#ef4444;">ERROR</span>
                                @endif
                            @else
                                <span class="text-muted" style="font-size: 0.75rem;">—</span>
                            @endif
                        </td>
                        <td class="text-right bold" style="color: var(--primary); font-size: 1rem;" data-label="Total">
                            @if ($esSalida && $salida)
                                $ {{ number_format($salida->total, 0, ',', '.') }}
                            @else
                                <span class="text-muted" style="font-size: 0.8rem;">Stock: +{{ $mov->cantidad }}</span>
                            @endif
                        </td>
                        <td data-label="Fecha">{{ \Carbon\Carbon::parse($mov->fecha)->format('d/m/Y') }}</td>
                        <td class="actions-cell text-center" data-label="Acciones">
                            <div class="movimientos-actions">
                                @if ($esSalida && $salida)
                                    @if ($salida->url_factus)
                                        <a href="{{ $salida->url_factus }}" target="_blank"
                                            class="btn-compact btn-factus" title="Ver en Factus">DIAN</a>
                                    @endif
                                    <a href="{{ route('salidas.factura', $salida->id) }}" target="_blank"
                                        class="btn-compact btn-pdf-c" title="Ver PDF">PDF</a>
                                    <a href="{{ route('salidas.factura.download', $salida->id) }}"
                                        class="btn-compact btn-download-c" title="Descargar">DESC</a>
                                    <a href="{{ route('salidas.factura', $salida->id) }}" target="_blank"
                                        class="btn-compact btn-print-c" title="Imprimir">IMP</a>
                                    <form action="{{ route('salidas.destroy', $salida->id) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button class="btn-compact btn-delete-c"
                                            onclick="return confirm('¿Confirmar?')">X</button>
                                    </form>
                                @else
                                    {{-- Acciones simples para entrada --}}
                                    <form action="{{ route('movimientos.destroy', $mov->id) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button class="btn-compact btn-delete-c"
                                            onclick="return confirm('¿Confirmar eliminación del movimiento?')">X</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">Sin registros encontrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
