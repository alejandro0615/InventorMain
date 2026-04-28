@extends('layouts.admin')

@section('title', 'CENTRO DE CONTROL · Usuarios')
@section('header_title', 'Gestión de Cuentas')
@section('header_subtitle', 'Administración global de accesos y clientes del sistema')

@section('content')
<div class="admin-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h2 style="font-size: 1.5rem; font-weight: 800; margin: 0;">Directorio de Clientes</h2>
            <div style="width: 50px; height: 4px; background: var(--admin-accent); border-radius: 2px; margin-top: 0.5rem;"></div>
        </div>
        <button onclick="toggleModal('modalNuevoUsuario')" class="admin-btn-primary">
            <span>✨</span> Registrar Nueva Cuenta
        </button>
    </div>

    @if (session('status'))
        <div style="background: rgba(139, 92, 246, 0.1); color: var(--admin-accent); padding: 1rem; border-radius: 12px; border: 1px solid rgba(139, 92, 246, 0.2); margin-bottom: 2rem; font-weight: 700;">
            {{ session('status') }}
        </div>
    @endif

    @if (session('error'))
        <div style="background: rgba(239, 68, 68, 0.1); color: #ef4444; padding: 1rem; border-radius: 12px; border: 1px solid rgba(239, 68, 68, 0.2); margin-bottom: 2rem; font-weight: 700;">
            ❌ {{ session('error') }}
        </div>
    @endif

    <div class="admin-table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Empresa / Negocio</th>
                    <th>Responsable</th>
                    <th>Acceso</th>
                    <th>Contacto</th>
                    <th>Estado</th>
                    <th style="text-align: center;">Control</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>
                        <div style="font-weight: 800; color: #fff;">{{ $user->razon_social }}</div>
                        <div style="font-size: 0.75rem; color: var(--admin-accent);">{{ $user->negocio->nombre ?? 'Sin Local' }}</div>
                    </td>
                    <td style="font-weight: 600;">{{ $user->name }}</td>
                    <td>
                        <div style="font-size: 0.85rem;">{{ $user->email }}</div>
                    </td>
                    <td style="font-size: 0.85rem; color: var(--admin-text-muted);">{{ $user->telefono }}</td>
                    <td>
                        <span class="status-badge {{ $user->estado === 'activo' ? 'status-active' : 'status-blocked' }}">
                            {{ $user->estado }}
                        </span>
                    </td>
                    <td style="text-align: center;">
                        <div style="display: flex; gap: 0.75rem; justify-content: center;">
                            <form action="{{ route('admin.users.toggle', $user) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" style="background: {{ $user->estado === 'activo' ? 'rgba(245, 158, 11, 0.1)' : 'rgba(16, 185, 129, 0.1)' }}; color: {{ $user->estado === 'activo' ? '#f59e0b' : '#10b981' }}; border: 1px solid {{ $user->estado === 'activo' ? 'rgba(245, 158, 11, 0.2)' : 'rgba(16, 185, 129, 0.2)' }}; padding: 0.5rem 1rem; border-radius: 8px; cursor: pointer; font-size: 0.75rem; font-weight: 700;">
                                    {{ $user->estado === 'activo' ? 'BLOQUEAR' : 'ACTIVAR' }}
                                </button>
                            </form>
                            
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('¿Eliminar esta cuenta permanentemente?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.2); padding: 0.5rem; border-radius: 8px; cursor: pointer;">
                                    🗑️
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div style="margin-top: 2rem;">
        {{ $users->links() }}
    </div>
</div>

<!-- Modal Registro Nuevo Usuario -->
<div id="modalNuevoUsuario" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.8); backdrop-filter: blur(8px); z-index: 2000; align-items: center; justify-content: center;">
    <div class="admin-card" style="max-width: 700px; width: 90%; border-color: var(--admin-accent);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <h3 style="font-size: 1.5rem; font-weight: 800; color: var(--admin-accent);">Nueva Incorporación</h3>
            <button onclick="toggleModal('modalNuevoUsuario')" style="background: none; border: none; font-size: 2rem; cursor: pointer; color: var(--admin-text-muted);">×</button>
        </div>
        
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                <div>
                    <label>Nombre del Responsable</label>
                    <input type="text" name="name" required placeholder="Ej: Juan Pérez">
                </div>
                <div>
                    <label>Correo Electrónico</label>
                    <input type="email" name="email" required placeholder="email@ejemplo.com">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-top: 1.5rem;">
                <div>
                    <label>Razón Social / Empresa</label>
                    <input type="text" name="razon_social" required placeholder="Nombre legal">
                </div>
                <div>
                    <label>NIT / Documento</label>
                    <input type="text" name="nit" required placeholder="Sin puntos ni guiones">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-top: 1.5rem;">
                <div>
                    <label>Nombre Comercial Local</label>
                    <input type="text" name="nombre_local" required placeholder="Ej: Tienda Centro">
                </div>
                <div>
                    <label>Teléfono de Contacto</label>
                    <input type="text" name="telefono" required placeholder="300 000 0000">
                </div>
            </div>

            <div style="margin-top: 1.5rem; margin-bottom: 2rem; position: relative;">
                <label>Contraseña de Acceso Inicial</label>
                <input type="password" id="new_user_password" name="password" required minlength="8" placeholder="Mínimo 8 caracteres" style="padding-right: 2.5rem;">
                <button type="button" onclick="togglePassword('new_user_password')" style="position: absolute; right: 0.75rem; top: 2.2rem; background: none; border: none; cursor: pointer; color: var(--admin-text-muted); font-size: 1.2rem;">👁️</button>
            </div>

            <div style="display: flex; gap: 1rem;">
                <button type="submit" class="admin-btn-primary" style="flex: 2; justify-content: center;">Confirmar Registro</button>
                <button type="button" onclick="toggleModal('modalNuevoUsuario')" style="flex: 1; background: rgba(255,255,255,0.05); color: white; border: 1px solid var(--admin-border); border-radius: 12px; font-weight: 700; cursor: pointer;">Cancelar</button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleModal(id) {
        const modal = document.getElementById(id);
        modal.style.display = modal.style.display === 'none' ? 'flex' : 'none';
    }

    function togglePassword(id) {
        const input = document.getElementById(id);
        input.type = input.type === 'password' ? 'text' : 'password';
    }
</script>
@endsection
