<div class="card-body">

    <div class="profile-grid">

        <!-- Columna izquierda -->
        <div class="profile-box">
            <h6 class="profile-title">Datos personales</h6>

            <div class="profile-item">
                <span>Nombre</span>
                <strong>{{ $usuario->nombre_completo }}</strong>
            </div>

            <div class="profile-item">
                <span>Email</span>
                <strong>{{ $usuario->email }}</strong>
            </div>

            <div class="profile-item">
                <span>Teléfono</span>
                <strong>{{ $usuario->telefono ?? 'No registrado' }}</strong>
            </div>

            <div class="profile-item">
                <span>Estado</span>
                <span class="tag {{ $usuario->is_activo ? 'tag-green' : 'tag-red' }}">
                    {{ ucfirst($usuario->estado) }}
                </span>
            </div>
        </div>

        <!-- Columna derecha -->
        <div class="profile-box">
            <h6 class="profile-title">Datos de la empresa</h6>

            <div class="profile-item">
                <span>Razón social</span>
                <strong>{{ $usuario->razon_social ?? 'No aplica' }}</strong>
            </div>

            <div class="profile-item">
                <span>NIT</span>
                <strong>{{ $usuario->nit ?? 'No registrado' }}</strong>
            </div>

            <div class="profile-item">
                <span>RUT</span>
                <strong>{{ $usuario->rut ?? 'No registrado' }}</strong>
            </div>

            <div class="profile-item">
                <span>Local</span>
                <strong>{{ $usuario->negocio?->nombre ?? 'Sin local asignado' }}</strong>
            </div>
        </div>

    </div>
</div>