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

    {{-- Cambio de Contraseña --}}
    <div class="card" style="margin-top: 2rem; max-width: 600px;">
        <h3 class="card-title" style="font-size: 0.85rem; font-weight: 800; text-transform: uppercase; margin-bottom: 1.5rem; color: var(--primary);">
            🔒 Cambiar Contraseña
        </h3>

        @if ($errors->any())
            <div style="background: rgba(239, 68, 68, 0.1); color: #ef4444; padding: 1rem; border-radius: 0.5rem; border: 1px solid rgba(239, 68, 68, 0.2); margin-bottom: 1.5rem; font-size: 0.8rem;">
                <ul style="margin: 0; padding-left: 1.5rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('perfil.password') }}" method="POST">
            @csrf
            <div style="margin-bottom: 1rem; position: relative;">
                <label style="display: block; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; color: var(--text-muted); margin-bottom: 0.5rem;">Contraseña Actual</label>
                <input type="password" id="current_password" name="current_password" required style="width: 100%; padding: 0.75rem; border-radius: 0.5rem; border: 1px solid var(--border-color); background: var(--bg-body); color: var(--text-main); padding-right: 2.5rem;">
                <button type="button" onclick="togglePassword('current_password')" style="position: absolute; right: 0.75rem; top: 2.2rem; background: none; border: none; cursor: pointer; color: var(--text-muted);">👁️</button>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                <div style="position: relative;">
                    <label style="display: block; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; color: var(--text-muted); margin-bottom: 0.5rem;">Nueva Contraseña</label>
                    <input type="password" id="password" name="password" required minlength="8" style="width: 100%; padding: 0.75rem; border-radius: 0.5rem; border: 1px solid var(--border-color); background: var(--bg-body); color: var(--text-main); padding-right: 2.5rem;">
                    <button type="button" onclick="togglePassword('password')" style="position: absolute; right: 0.75rem; top: 2.2rem; background: none; border: none; cursor: pointer; color: var(--text-muted);">👁️</button>
                </div>
                <div style="position: relative;">
                    <label style="display: block; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; color: var(--text-muted); margin-bottom: 0.5rem;">Confirmar Nueva Contraseña</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required minlength="8" style="width: 100%; padding: 0.75rem; border-radius: 0.5rem; border: 1px solid var(--border-color); background: var(--bg-body); color: var(--text-main); padding-right: 2.5rem;">
                    <button type="button" onclick="togglePassword('password_confirmation')" style="position: absolute; right: 0.75rem; top: 2.2rem; background: none; border: none; cursor: pointer; color: var(--text-muted);">👁️</button>
                </div>
            </div>

            <button type="submit" class="btn-primary-action" style="width: 100%; justify-content: center;">
                Actualizar Credenciales
            </button>
        </form>
    </div>
    
    <script>
        function togglePassword(id) {
            const input = document.getElementById(id);
            input.type = input.type === 'password' ? 'text' : 'password';
        }
    </script>
    
    <div style="margin-top: 2rem; text-align: left;">
        <p class="text-muted" style="font-size: 0.75rem; font-style: italic;">
            Para modificar estos datos, por favor contacte con el administrador del sistema.
        </p>
    </div>
</div>