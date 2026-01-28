<div class="card">
    <div class="flex items-center justify-between mb-4">
        <h2 class="card-title">Productos</h2>
        <a href="{{ route('productos.create') }}"
            class="inline-flex items-center px-4 py-2 rounded-md shadow text-sm font-medium"
            style="background:#2563eb; color:#ffffff; border:none;">
            Nuevo producto
        </a>
    </div>

    @if (session('success'))
        <div class="mb-4 px-4 py-2 text-sm text-green-800 bg-green-100 border border-green-200 rounded-md">
            {{ session('success') }}
        </div>
    @endif

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

                        {{-- Cantidad actual con color según stock --}}
                        <td class="text-left">
                            <span
                                @if ($esBajo) style="color:#f87171; font-weight:600;"
                                @else
                                    style="{{ request()->has('dark_mode') ? 'color:#e5e7eb;' : 'color:#111827;' }}" @endif>
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
    </div>
</div>
