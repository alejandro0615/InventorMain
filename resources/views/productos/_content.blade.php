<div class="card">
    <div class="flex items-center justify-between mb-4">
        <h2 class="card-title">Productos</h2>
        {{-- Botón NUEVO PRODUCTO --}}
        <a href="{{ route('productos.create') }}"
            class="inline-flex items-center px-4 py-2 rounded-md shadow text-sm font-medium"
            style="background:#2563eb; color:#ffffff; border:none;">
            Nuevo producto
        </a>
    </div>

    {{-- Barra de búsqueda + filtros --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
        <form method="GET" action="{{ route('productos.index') }}" class="flex gap-2 w-full md:w-auto">
            <input type="text" name="q" placeholder="Buscar por código o nombre" value="{{ $search ?? '' }}"
                class="flex-1 px-3 py-1.5 rounded-md text-sm border border-gray-300" />

            {{-- mantener el filtro actual en la búsqueda --}}
            @if (request('filtro'))
                <input type="hidden" name="filtro" value="{{ request('filtro') }}">
            @endif

            <button type="submit" class="btn-tour" style="background:#2563eb;">
                Buscar
            </button>

            @if (!empty($search))
                <a href="{{ route('productos.index', array_filter(['filtro' => request('filtro')])) }}" class="btn-tour"
                    style="background:#1645a4; text-decoration:none;">
                    Limpiar
                </a>
            @endif
        </form>

        {{-- Grupo de filtros --}}
        <div class="flex items-center gap-2">
            <div class="inline-flex items-center gap-1 bg-slate-100 rounded-full px-1 py-1">
                <a href="{{ route('productos.index', array_filter(['q' => $search ?? null])) }}"
                    class="filter-pill {{ request('filtro') ? 'bg-transparent text-slate-700' : 'bg-blue-600 text-white filter-pill--active' }}">
                    Todos
                </a>

                <a href="{{ route('productos.index', array_filter(['filtro' => 'stock_bajo', 'q' => $search ?? null])) }}"
                    class="filter-pill {{ request('filtro') === 'stock_bajo' ? 'bg-red-600 text-white filter-pill--active' : 'bg-transparent text-slate-700' }}">
                    Stock bajo (≤ 5)
                </a>
            </div>
        </div>
    </div>
</div>
{{-- … resto de la tabla igual … --}}


{{-- session('success') --}}
@if (session('success'))
    <div class="mb-4 px-4 py-2 text-sm text-green-800 bg-green-100 border border-green-200 rounded-md">
        {{ session('success') }}
    </div>
@endif

{{-- Tabla --}}
<div class="overflow-x-auto table-wrapper">
    <table class="min-w-full table-auto text-sm">
        <thead class="border-b border-gray-200">
            <tr>
                <th class="text-left font-semibold">CODIGO</th>
                <th class="text-left font-semibold">Nombre</th>
                <th class="text-left font-semibold">Precio</th>
                <th class="text-left font-semibold">Cantidad actual</th>
                <th class="text-right font-semibold">Acciones</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($productos as $producto)
                @php
                    $stock = $producto->stock_actual;
                    $esBajo = $stock <= 5; // umbral de alerta
                @endphp

                <tr class="border-t border-gray-200 hover:bg-gray-50">
                    <td>{{ $producto->codigo }}</td>
                    <td>{{ $producto->nombre }}</td>

                    {{-- Precio --}}
                    <td class="text-left">
                        {{ '$ ' . number_format($producto->precio_venta, 0, ',', '.') }}
                    </td>


                    <td class="text-left">
                        <span class="@if ($esBajo) stock-bajo @else stock-normal @endif">
                            {{ $stock }}
                        </span>
                    </td>

                    <td class="align-middle text-right">
                        <div class="actions">
                            <a href="{{ route('productos.edit', $producto) }}" class="text-xs px-3 py-1 rounded-md"
                                style="background:#eab308; color:#ffffff;">
                                Editar
                            </a>

                            <form action="{{ route('productos.destroy', $producto) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs px-3 py-1 rounded-md"
                                    style="background:#dc2626; color:#ffffff;"
                                    onclick="return confirm('¿Eliminar este producto?')">
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="py-4 text-center text-gray-500">
                        No hay productos registrados.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">
        {{ $productos->links() }}
    </div>
</div>