<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de inicio · Inventario</title>
    <style>
        :root {
            --bg-gradient: radial-gradient(circle at top, #1d4ed8, #0f172a);
            --card-bg: rgba(15, 23, 42, 0.96);
            --card-border: rgba(148, 163, 184, 0.3);
            --accent: #22c55e;
            --accent-soft: rgba(34, 197, 94, 0.14);
            --text-main: #e5e7eb;
            --text-muted: #9ca3af;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background: var(--bg-gradient);
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            color: var(--text-main);
        }

        .home-container {
            width: 100%;
            max-width: 960px;
            background: radial-gradient(circle at top left, rgba(56, 189, 248, 0.18), transparent 55%),
                radial-gradient(circle at bottom right, rgba(34, 197, 94, 0.18), transparent 55%),
                rgba(15, 23, 42, 0.96);
            border-radius: 1.25rem;
            padding: 1.75rem 2rem 2.1rem;
            box-shadow:
                0 24px 60px rgba(15, 23, 42, 0.7),
                0 0 0 1px rgba(148, 163, 184, 0.18);
            backdrop-filter: blur(14px);
        }

        .home-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1.25rem;
            margin-bottom: 1.6rem;
        }

        .home-title-block {
            max-width: 60%;
        }

        .home-kicker {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.15rem 0.6rem;
            border-radius: 999px;
            background: rgba(15, 23, 42, 0.7);
            border: 1px solid rgba(148, 163, 184, 0.45);
            font-size: 0.7rem;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-bottom: 0.4rem;
        }

        .home-title {
            margin: 0;
            font-size: 1.6rem;
            font-weight: 700;
            background: linear-gradient(90deg, #e5e7eb, #a7f3d0);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .home-subtitle {
            margin: 0.3rem 0 0;
            font-size: 0.9rem;
            color: var(--text-muted);
        }

        .home-user {
            text-align: right;
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        .home-user strong {
            color: #e5e7eb;
        }

        .home-user form {
            display: inline-block;
            margin-top: 0.4rem;
        }

        .logout-btn {
            padding: 0.35rem 0.85rem;
            font-size: 0.8rem;
            background: linear-gradient(135deg, #f97373, #f43f5e);
            color: #ffffff;
            border-radius: 999px;
            border: none;
            cursor: pointer;
            border: 1px solid rgba(248, 113, 113, 0.7);
            box-shadow: 0 8px 18px rgba(248, 113, 113, 0.35);
            transition: transform 0.12s ease, box-shadow 0.12s ease, filter 0.12s ease;
        }

        .logout-btn:hover {
            transform: translateY(-1px);
            filter: brightness(1.05);
            box-shadow: 0 12px 26px rgba(248, 113, 113, 0.45);
        }

        .home-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
            gap: 1rem;
        }

        .card {
            background: var(--card-bg);
            border-radius: 0.9rem;
            padding: 0.95rem 1rem;
            border: 1px solid var(--card-border);
            box-shadow:
                0 14px 30px rgba(15, 23, 42, 0.65),
                0 0 0 1px rgba(15, 23, 42, 0.8);
            position: relative;
            overflow: hidden;
            transition: transform 0.16s ease, box-shadow 0.16s ease, border-color 0.16s ease, background 0.16s ease;
        }

        .card::before {
            content: "";
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at top left, rgba(59, 130, 246, 0.22), transparent 55%);
            opacity: 0;
            transition: opacity 0.16s ease;
            pointer-events: none;
        }

        .card:hover {
            transform: translateY(-3px);
            border-color: rgba(96, 165, 250, 0.7);
            box-shadow:
                0 22px 45px rgba(15, 23, 42, 0.9),
                0 0 0 1px rgba(59, 130, 246, 0.8);
            background: linear-gradient(145deg,
                    rgba(15, 23, 42, 0.98),
                    rgba(15, 23, 42, 0.94));
        }

        .card:hover::before {
            opacity: 1;
        }

        .card-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.15rem 0.55rem;
            border-radius: 999px;
            background: var(--accent-soft);
            color: #bbf7d0;
            font-size: 0.7rem;
            margin-bottom: 0.35rem;
        }

        .card-title {
            font-size: 0.95rem;
            font-weight: 600;
            color: #e5e7eb;
            margin: 0;
        }

        .card-text {
            font-size: 0.82rem;
            color: var(--text-muted);
            margin-top: 0.55rem;
            line-height: 1.6;
            letter-spacing: 0.015em;
        }

        .card-tag {
            font-size: 0.75rem;
            color: #a5b4fc;
            margin-top: 0.45rem;
        }

        @media (max-width: 640px) {
            body {
                padding: 1rem;
            }

            .home-container {
                padding: 1.35rem 1.4rem 1.6rem;
            }

            .home-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .home-title-block {
                max-width: 100%;
            }

            .home-user {
                text-align: left;
            }
        }
    </style>
</head>

<body>
    <div class="home-container">
        <div class="home-header">
            <div class="home-title-block">
                <div class="home-kicker">
                    <span>Inventario</span>
                    <span>·</span>
                    <span>Panel inicial</span>
                </div>
                <h1 class="home-title">Panel de inventario</h1>
                <p class="home-subtitle">
                    Un vistazo rápido a los módulos principales para controlar existencias, movimientos y documentos.
                </p>
            </div>

            <div class="home-user">
                <div>Sesión iniciada como <strong>{{ auth()->user()->name ?? 'Usuario' }}</strong></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn">Cerrar sesión</button>
                </form>
            </div>
        </div>

        <div class="home-grid">
            <div class="card">
                <div class="card-pill">Catálogo</div>
                <h2 class="card-title">Productos</h2>
                <p class="card-text">
                    Registra y actualiza tus productos, controla precios, stock mínimo y estado de cada referencia.
                </p>
                <div class="card-tag">Gestión completa del inventario base</div>
            </div>

            <div class="card">
                <div class="card-pill">Operaciones</div>
                <h2 class="card-title">Movimientos</h2>
                <p class="card-text">
                    Lleva el detalle de entradas, salidas y ajustes para mantener un historial claro por producto.
                </p>
                <div class="card-tag">Trazabilidad de cada cambio de stock</div>
            </div>

            <div class="card">
                <div class="card-pill">Relaciones</div>
                <h2 class="card-title">Catálogo de terceros</h2>
                <p class="card-text">
                    Administra tus proveedores y contactos, y asocia cada movimiento a su origen correspondiente.
                </p>
                <div class="card-tag">Información centralizada de proveedores</div>
            </div>

            <div class="card">
                <div class="card-pill">Documentos</div>
                <h2 class="card-title">Cotizaciones y facturas</h2>
                <p class="card-text">
                    Genera cotizaciones y facturas apoyadas en el inventario, manteniendo ordenado el flujo de ventas.
                </p>
                <div class="card-tag">Seguimiento de propuestas y ventas cerradas</div>
            </div>
        </div>
    </div>
</body>

</html>
