<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro · Inventario</title>

    <style>
        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background: radial-gradient(circle at top, #dbeafe, #eff6ff);
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 1rem;
        }

        .register-container {
            background: #ffffff;
            padding: 2.5rem;
            border-radius: 1rem;
            box-shadow: 0 20px 40px rgba(15, 23, 42, 0.15);
            width: 100%;
            max-width: 450px;
        }

        .mascot {
            width: 120px;
            margin: -90px auto 20px;
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-8px);
            }
        }

        .register-title {
            margin: 0 0 1.5rem;
            font-size: 1.5rem;
            color: #1e3a8a;
            font-weight: 700;
            text-align: center;
        }

        .form-group {
            margin-bottom: 1.25rem;
            text-align: left;
        }

        label {
            display: block;
            margin-bottom: 0.4rem;
            font-size: 0.9rem;
            color: #374151;
            font-weight: 500;
        }

        input,
        select {
            width: 100%;
            padding: 0.75rem;
            border-radius: 0.6rem;
            border: 1px solid #d1d5db;
            font-size: 0.95rem;
            transition: all 0.2s;
            box-sizing: border-box;
        }

        input:focus,
        select:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px #3b82f630;
            outline: none;
        }

        .btn-primary {
            width: 100%;
            padding: 0.8rem;
            background: linear-gradient(90deg, #22c55e, #16a34a);
            color: #ffffff;
            border: none;
            border-radius: 0.6rem;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            margin-top: 1rem;
        }

        .btn-primary:hover {
            filter: brightness(1.05);
            transform: translateY(-1px);
        }

        .error-message {
            color: #dc2626;
            font-size: 0.85rem;
            margin-top: 0.25rem;
            display: block;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .form-row .form-group {
            margin-bottom: 1.25rem;
        }

        @media (max-width: 480px) {
            .form-row {
                grid-template-columns: 1fr;
            }
        }

        .info-text {
            font-size: 0.8rem;
            color: #6b7280;
            margin-top: 0.25rem;
            font-style: italic;
        }
    </style>
</head>

<body>

    <div class="register-container">
        <!-- 🧸 MUÑEQUITO -->
        <svg class="mascot" viewBox="0 0 200 200">
            <circle cx="100" cy="100" r="90" fill="#93c5fd" />
            <circle cx="70" cy="90" r="10" fill="#1e3a8a" />
            <circle cx="130" cy="90" r="10" fill="#1e3a8a" />
            <path d="M70 125 Q100 145 130 125" stroke="#1e3a8a" stroke-width="6" fill="none" />
            <circle cx="65" cy="90" r="4" fill="#fff" />
            <circle cx="125" cy="90" r="4" fill="#fff" />
        </svg>

        <h1 class="register-title">Crear cuenta empresarial</h1>

        @if ($errors->any())
            <div
                style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 0.5rem; padding: 1rem; margin-bottom: 1.5rem;">
                <div style="color: #dc2626; font-size: 0.9rem;">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('register.store') }}">
            @csrf

            <!-- DATOS PERSONALES -->
            <div class="form-group">
                <label for="name">Nombre completo <span style="color: #dc2626;">*</span></label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                    placeholder="Juan Pérez">
                @error('name')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Correo electrónico <span style="color: #dc2626;">*</span></label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                    placeholder="empresa@ejemplo.com">
                @error('email')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <!-- DATOS EMPRESARIALES -->
            <div class="form-group">
                <label for="razon_social">Razón social <span style="color: #dc2626;">*</span></label>
                <input type="text" id="razon_social" name="razon_social" value="{{ old('razon_social') }}" required
                    placeholder="Mi Empresa SAS">
                @error('razon_social')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="nit">
                    NIT <span style="color:#dc2626;">*</span>
                </label>

                <input type="text" id="nit" name="nit" value="{{ old('nit') }}" required maxlength="12"
                    placeholder="900123456-1" pattern="^[0-9]{9,10}-[0-9]$" title="Formato válido: 900123456-1">

                <div class="info-text">
                    Ej: 900123456-1 (9–10 números, guion y dígito verificador)
                </div>

                @error('nit')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <!-- CONTACTO Y LOCAL -->
            <div class="form-row">
                <div class="form-group">
                    <label for="telefono">Teléfono <span style="color: #dc2626;">*</span></label>
                    <input type="tel" id="telefono" name="telefono" value="{{ old('telefono') }}" required
                        placeholder="+573001234567">
                    <div class="info-text">Ej: +573001234567</div>
                    @error('telefono')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="nombre_local">Nombre del local <span style="color: #dc2626;">*</span></label>
                    <input type="text" id="nombre_local" name="nombre_local" value="{{ old('nombre_local') }}"
                        required placeholder="Tienda Centro, Bodega Principal, etc.">
                    @error('nombre_local')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

            </div>

            <div class="form-group">
                <label for="password">
                    Contraseña <span style="color:#dc2626;">*</span>
                </label>

                <div style="position:relative;">
                    <input type="password" id="password" name="password" required minlength="8"
                        style="padding-right:40px;">

                    <button type="button" class="toggle-btn" onclick="togglePassword('password', this)">👁️</button>
                </div>

                @error('password')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">
                    Confirmar contraseña <span style="color:#dc2626;">*</span>
                </label>

                <div style="position:relative;">
                    <input type="password" id="password_confirmation" name="password_confirmation" required
                        minlength="8" placeholder="Repite tu contraseña" style="padding-right:40px;">

                    <button type="button" class="toggle-btn"
                        onclick="togglePassword('password_confirmation', this)">👁️</button>
                </div>

                @error('password_confirmation')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>


            <input type="hidden" name="estado" value="activo">

            <button type="submit" class="btn-primary">
                📦 ¡Registrarme y activar mi inventario!
            </button>
        </form>
    </div>

</body>

</html>
