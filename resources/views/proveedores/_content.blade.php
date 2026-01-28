<div class="card">
    <div class="flex items-center justify-between mb-4">
        <h2 class="card-title">Proveedores</h2>
        <a href="{{ route('proveedores.create') }}"
            class="inline-flex items-center px-4 py-2 rounded-md shadow text-sm font-medium"
            style="background:#2563eb; color:#ffffff; border:none;">
            Nuevo proveedor
        </a>
    </div>
    {{-- Aquí pones tu tabla, formulario, etc. --}}
    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>NIT</th>
                <th>Teléfono</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($proveedores as $proveedor)
                <tr>
                    <td>{{ $proveedor->nombre }}</td>
                    <td>{{ $proveedor->nit }}</td>
                    <td>{{ $proveedor->telefono }}</td>

                    <td class="px-4 py-2 text-right space-x-2">
                        <a href="{{ route('proveedores.edit', $proveedor) }}" class="text-xs px-3 py-1 rounded-md"
                            style="background:#eab308; color:#ffffff; border:none;">
                            Editar
                        </a>
                    </td>
                    <td>
                        <form action="{{ route('proveedores.destroy', $proveedor) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs px-3 py-1 rounded-md"
                                style="background:#dc2626; color:#ffffff; border:none;"
                                onclick="return confirm('¿Deseas eliminar este proveedor?')">
                                Eliminar
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">No hay proveedores registrados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
