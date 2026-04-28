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
    </div>

    {{-- TABLA --}}
    <div id ="tourt-table" class="table-wrapper">
        <table>
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
                        <td class="bold">{{ $producto->codigo }}</td>
                        <td>{{ $producto->nombre }}</td>
                        <td class="bold" style="color: var(--primary);">$ {{ number_format($producto->precio_venta, 0, ',', '.') }}</td>
                        <td>
                            <span style="font-weight: 800; color: {{ $esBajo ? '#ef4444' : '#10b981' }};">
                                {{ $producto->stock_actual }} UND
                            </span>
                        </td>
                        <td class="text-right">
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