<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login · Inventario</title>

    <style>
        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background: radial-gradient(circle at top, #dbeafe, #eff6ff);
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .login-container {
            background: #ffffff;
            padding: 2rem 2.5rem;
            border-radius: 1rem;
            box-shadow: 0 20px 40px rgba(15, 23, 42, 0.15);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        /* 🧸 MASCOTA */
        .mascot {
            width: 120px;
            margin: -90px auto 10px;
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

        .login-title {
            margin: 0 0 1.2rem;
            font-size: 1.4rem;
            color: #1e3a8a;
            font-weight: 700;
        }

        .form-group {
            margin-bottom: 1rem;
            text-align: left;
        }

        label {
            display: block;
            margin-bottom: 0.25rem;
            font-size: 0.9rem;
            color: #374151;
        }

        input {
            width: 100%;
            padding: 0.6rem 0.7rem;
            border-radius: 0.6rem;
            border: 1px solid #d1d5db;
            font-size: 0.95rem;
            outline: none;
        }

        input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 2px #3b82f630;
        }

        .btn-primary {
            width: 100%;
            padding: 0.65rem;
            background: linear-gradient(90deg, #3b82f6, #2563eb);
            color: #ffffff;
            border: none;
            border-radius: 0.6rem;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            margin-top: 0.5rem;
        }

        .btn-primary:hover {
            filter: brightness(1.1);
        }

        .error {
            color: #dc2626;
            font-size: 0.85rem;
            margin-bottom: 0.4rem;
        }

        .footer-text {
            margin-top: 1rem;
            font-size: 0.8rem;
            color: #6b7280;
        }
    </style>
</head>

<body>

    <div class="login-container">

        <!-- 🧸 MUÑEQUITO SVG -->
        <svg class="mascot" viewBox="0 0 200 200">
            <circle cx="100" cy="100" r="90" fill="#93c5fd" />
            <circle cx="70" cy="90" r="10" fill="#1e3a8a" />
            <circle cx="130" cy="90" r="10" fill="#1e3a8a" />
            <path d="M70 125 Q100 145 130 125" stroke="#1e3a8a" stroke-width="6" fill="none" />
            <circle cx="65" cy="90" r="4" fill="#fff" />
            <circle cx="125" cy="90" r="4" fill="#fff" />
        </svg>

        <h1 class="login-title">¡Bienvenido!</h1>

        @if (session('error'))
            <div class="error">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('login.store') }}">
            @csrf

            <div class="form-group">
                <label for="email">Correo</label>
                <input type="email" name="email" value="{{ old('email') }}" required>
                @error('email')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Contraseña</label>

                <div style="position: relative;">
                    <input type="password" name="password" id="password" required style="padding-right: 40px;">

                    <button type="button" onclick="togglePassword('password', this)"
                        style="
                position: absolute;
                right: 8px;
                top: 50%;
                transform: translateY(-50%);
                background: none;
                border: none;
                cursor: pointer;
                font-size: 18px;"
                        aria-label="Mostrar contraseña">
                        👁️
                    </button>
                </div>

                @error('password')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn-primary">
                Entrar 🚀
            </button>
        </form>

        <p class="footer-text">
            Sistema de inventario · Acceso seguro
        </p>
    </div>

    <script>
        function togglePassword(id, btn) {
            const input = document.getElementById(id);

            if (input.type === "password") {
                input.type = "text";
                btn.textContent = "🙈";
            } else {
                input.type = "password";
                btn.textContent = "👁️";
            }
        }
    </script>

</body>

</html>
