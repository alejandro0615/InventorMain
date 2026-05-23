<div class="card">
    <style>
        .page-header {
            margin-bottom: 1.5rem;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 0.5rem;
        }

        .search-container {
            width: 100%;
            margin-bottom: 1rem;
        }

        .search-container input {
            width: 100%;
            padding: 1rem;
            font-size: 1rem;
            border-radius: 0.75rem;
            background: var(--bg-body);
            border: 2px solid var(--border-color);
            transition: border-color 0.2s;
        }

        .search-container input:focus {
            border-color: var(--primary);
            outline: none;
        }

        .filter-section {
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .action-section {
            margin-bottom: 1.5rem;
            display: flex;
            justify-content: flex-start;
        }

        .filter-pill {
            padding: 0.5rem 1.2rem;
            border-radius: 2rem;
            font-size: 0.75rem;
            font-weight: 800;
            text-decoration: none;
            background: rgba(148, 163, 184, 0.05);
            color: var(--text-muted);
            text-transform: uppercase;
            border: 1px solid var(--border-color);
        }

        .filter-pill.active {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
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

        .products-table-wrapper {
            border-radius: 1rem;
            overflow: hidden;
            border: 1px solid var(--border-color);
            background: var(--bg-card);
            box-shadow: var(--card-shadow);
        }

        .products-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .products-table thead th {
            background: transparent;
        }

        .products-table th,
        .products-table td {
            white-space: nowrap;
        }

        @media (max-width: 768px) {
            .page-header {
                margin-bottom: 1rem;
                padding-bottom: 0.75rem;
            }

            .search-container input {
                padding: 0.85rem 0.95rem;
                font-size: 0.95rem;
            }

            .filter-section {
                gap: 0.5rem;
                flex-wrap: wrap;
            }

            .action-section {
                margin-bottom: 1rem;
            }

            .products-table-wrapper {
                overflow: visible;
                -webkit-overflow-scrolling: touch;
            }

            .products-table {
                min-width: 0;
                background: transparent;
            }

            .products-table tr,
            .products-table td { background: transparent; }

            .products-table th,
            .products-table td {
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
            .products-table thead { display: none; }
            .products-table tbody,
            .products-table tr,
            .products-table td { display: block; width: 100%; }

            .products-table tbody tr {
                margin-bottom: 1rem;
                border: 1px solid var(--border-color);
                border-radius: 1rem;
                overflow: hidden;
                box-shadow: 0 12px 28px rgba(0, 0, 0, 0.25);
                background: linear-gradient(180deg, rgba(15, 23, 42, 0.98), rgba(30, 41, 59, 0.98));
            }

            .products-table td {
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
                gap: 1rem;
                padding: 0.9rem 1rem;
                font-size: 0.84rem;
                border-bottom: 1px solid rgba(148, 163, 184, 0.16);
                white-space: normal;
            }

            .products-table td:last-child { border-bottom: none; }

            .products-table td::before {
                content: attr(data-label);
                flex: 0 0 40%;
                font-size: 0.69rem;
                font-weight: 800;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                color: #cbd5e1;
                padding-top: 0.08rem;
            }

            .products-table td,
            .products-table td > * { color: #f8fafc; }

            .products-table td > * { text-align: right; }

            .products-table td[data-label="Acciones"] {
                flex-direction: column;
                align-items: stretch;
                gap: 0.5rem;
            }

            .products-table td[data-label="Acciones"]::before {
                width: 100%;
                flex: none;
            }

            .products-table td[data-label="Acciones"] .crud-actions {
                width: 100%;
                flex-direction: column;
            }

            .products-table td[data-label="Acciones"] .crud-action-btn {
                box-shadow: none;
            }

            /* Botones pequeños y consistentes (texto) */
            .products-table td[data-label="Acciones"] a,
            .products-table td[data-label="Acciones"] button {
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

    {{-- 1. TÍTULO --}}
    <div id ="tourt-titulo" class="page-header">
        <h2 class="card-title" style="font-size: 1.5rem; margin: 0; text-transform: uppercase;">Inventario de Productos</h2>
        <p class="text-muted" style="font-size: 0.8rem;">Administración de existencias y precios.</p>
    </div>

    {{-- 2. BARRA DE BÚSQUEDA GRANDE --}}
    <div id="tour-search" class="search-container">
        <form method="GET" action="{{ route('productos.index') }}">
            <input type="text" name="q" placeholder="Escribe el nombre o código del producto para buscar..." value="{{ $search ?? '' }}">
            @if(request('filtro'))
                <input type="hidden" name="filtro" value="{{ request('filtro') }}">
            @endif
        </form>
    </div>

    {{-- 3. STOCK BAJO (FILTROS) --}}
    <div id="tour-filters" class="filter-section">
        <a href="{{ route('productos.index', array_filter(['q' => $search ?? null])) }}" 
           class="filter-pill {{ !request('filtro') ? 'active' : '' }}">Todos los productos</a>
        <a href="{{ route('productos.index', array_filter(['filtro' => 'stock_bajo', 'q' => $search ?? null])) }}" 
           class="filter-pill {{ request('filtro') === 'stock_bajo' ? 'active' : '' }}">⚠️ Stock Bajo</a>
    </div>

    {{-- 4. BOTONES DE ACCIÓN (DEBAJO DE TODO) --}}
    <div id = "tourt-newProduct" class="action-section">
        <a href="{{ route('productos.create') }}" class="btn-primary-action">
            Nuevo Producto
        </a>
        <a href="{{ route('productos.export') }}" class="btn-download" style="margin-left:0.6rem; font-weight:800;">Exportar Excel</a>
    </div>

    {{-- TABLA --}}
    <div id ="tourt-table" class="table-wrapper products-table-wrapper">
        <table class="products-table">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Descripción</th>
                    <th>Precio Venta</th>
                    <th>Existencias</th>
                    <th class="text-right">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($productos as $producto)
                    @php $esBajo = $producto->stock_actual <= 5; @endphp
                    <tr>
                        <td class="bold" data-label="Código">{{ $producto->codigo }}</td>
                        <td data-label="Descripción">{{ $producto->nombre }}</td>
                        <td class="bold" data-label="Precio Venta" style="color: var(--primary);">$ {{ number_format($producto->precio_venta, 0, ',', '.') }}</td>
                        <td data-label="Existencias">
                            <span style="font-weight: 800; color: {{ $esBajo ? '#ef4444' : '#10b981' }};">
                                {{ $producto->stock_actual }} UND
                            </span>
                        </td>
                        <td class="text-right" data-label="Acciones">
                            <div class="crud-actions">
                                <a href="{{ route('productos.edit', $producto) }}" class="crud-action-btn">Editar</a>
                                <form action="{{ route('productos.destroy', $producto) }}" method="POST" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="crud-action-btn" style="background:#ef4444;" onclick="return confirm('¿Confirmar?')">Borrar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted">No se encontraron resultados para tu búsqueda.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 1.5rem;">
        {{ $productos->links() }}
    </div>
</div>