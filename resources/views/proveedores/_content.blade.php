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
    </style>

    {{-- Encabezado estándar --}}
    <div class="page-header">
        <h2 class="card-title" style="font-size: 1.5rem; margin: 0; text-transform: uppercase;">Directorio de Proveedores</h2>
        <p class="text-muted" style="font-size: 0.8rem;">Administración de contactos y entidades comerciales.</p>
        
        <div class="page-actions">
            <a href="{{ route('proveedores.create') }}" class="btn-primary-action">
                Nuevo Proveedor
            </a>
        </div>
    </div>

    <div class="table-wrapper">
        <table>
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
                        <td class="bold">{{ $proveedor->nombre }}</td>
                        <td>{{ $proveedor->nit }}</td>
                        <td>{{ $proveedor->telefono }}</td>
                        <td class="text-right">
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
                        <td colspan="4" class="text-center text-muted">No se encontraron proveedores registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
