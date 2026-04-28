<div class="profile-container" style="animation: fadeIn 0.4s ease;">
    <style>
        .page-header {
            margin-bottom: 2rem;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 1rem;
        }
        
        .profile-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid var(--border-color);
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            font-size: 0.7rem;
            font-weight: 800;
            text-transform: uppercase;
            color: var(--text-muted);
            letter-spacing: 0.05em;
        }

        .info-value {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-main);
        }

        .status-pill {
            font-size: 0.65rem;
            padding: 0.25rem 0.6rem;
            border-radius: 2rem;
            font-weight: 800;
            text-transform: uppercase;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(5px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>

    {{-- Encabezado del Perfil --}}
    <div class="page-header">
        <h2 class="card-title" style="font-size: 1.5rem; margin: 0; text-transform: uppercase;">Mi Perfil de Usuario</h2>
        <p class="text-muted" style="font-size: 0.8rem;">Gestión de información personal y credenciales corporativas.</p>
    </div>

    <div class="profile-grid">
        {{-- Datos Personales --}}
        <div class="card">
            <h3 class="card-title" style="font-size: 0.85rem; font-weight: 800; text-transform: uppercase; margin-bottom: 1.5rem; color: var(--primary);">
                Información Personal
            </h3>
            
            <div class="info-row">
                <span class="info-label">Nombre Completo</span>
                <span class="info-value">{{ $usuario->nombre_completo }}</span>
            </div>

            <div class="info-row">
                <span class="info-label">Correo Electrónico</span>
                <span class="info-value">{{ $usuario->email }}</span>
            </div>

            <div class="info-row">
                <span class="info-label">Teléfono</span>
                <span class="info-value">{{ $usuario->telefono ?? 'No registrado' }}</span>
            </div>

            <div class="info-row">
                <span class="info-label">Estado de Cuenta</span>
                <span class="status-pill" style="background: {{ $usuario->is_activo ? 'rgba(16, 185, 129, 0.1)' : 'rgba(239, 68, 68, 0.1)' }}; color: {{ $usuario->is_activo ? '#10b981' : '#ef4444' }};">
                    {{ $usuario->estado }}
                </span>
            </div>
        </div>

        {{-- Datos de la Empresa --}}
        <div class="card">
            <h3 class="card-title" style="font-size: 0.85rem; font-weight: 800; text-transform: uppercase; margin-bottom: 1.5rem; color: var(--primary);">
                Datos Corporativos
            </h3>

            <div class="info-row">
                <span class="info-label">Razón Social</span>
                <span class="info-value">{{ $usuario->razon_social ?? 'Persona Natural' }}</span>
            </div>

            <div class="info-row">
                <span class="info-label">NIT / Identificación</span>
                <span class="info-value">{{ $usuario->nit ?? 'No registrado' }}</span>
            </div>

            <div class="info-row">
                <span class="info-label">RUT</span>
                <span class="info-value">{{ $usuario->rut ?? 'No registrado' }}</span>
            </div>

            <div class="info-row">
                <span class="info-label">Sede / Local Asignado</span>
                <span class="info-value">{{ optional($usuario->negocio)->nombre ?? 'Administración Central' }}</span>
            </div>
        </div>
    </div>
    
    <div style="margin-top: 2rem; text-align: left;">
        <p class="text-muted" style="font-size: 0.75rem; font-style: italic;">
            Para modificar estos datos, por favor contacte con el administrador del sistema.
        </p>
    </div>
</div>