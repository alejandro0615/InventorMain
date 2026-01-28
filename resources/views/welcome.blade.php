<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de inicio · Inventario</title>
    <style>
        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background: #f3f4f6;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .home-container {
            background: #ffffff;
            padding: 2rem 2.5rem;
            border-radius: 0.75rem;
            box-shadow: 0 10px 25px rgba(15, 23, 42, 0.15);
            width: 100%;
            max-width: 900px;
        }
        .home-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        .home-title {
            margin: 0;
            font-size: 1.4rem;
            color: #111827;
        }
        .home-user {
            font-size: 0.9rem;
            color: #6b7280;
        }
        .home-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 1rem;
        }
        .card {
            background: #f9fafb;
            border-radius: 0.75rem;
            padding: 1rem;
            border: 1px solid #e5e7eb;
        }
        .card-title {
            font-size: 0.95rem;
            font-weight: 600;
            color: #111827;
            margin-bottom: 0.4rem;
        }
        .card-text {
            font-size: 0.85rem;
            color: #6b7280;
            margin: 0;
        }
        .logout-btn {
            padding: 0.45rem 0.9rem;
            font-size: 0.85rem;
            background: #ef4444;
            color: #ffffff;
            border-radius: 0.5rem;
            border: none;
            cursor: pointer;
        }
        .logout-btn:hover {
            background: #dc2626;
        }
    </style>
</head>
<body>
    <div class="home-container">
        <div class="home-header">
            <h1 class="home-title">Panel de Inventario</h1>
            <div class="home-user">
                Bienvenido, {{ auth()->user()->name ?? 'Usuario' }}
                <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="logout-btn">Cerrar sesión</button>
                </form>
            </div>
        </div>

        <div class="home-grid">
            <div class="card">
                <h2 class="card-title">Productos</h2>
                <p class="card-text">Gestiona el catálogo de productos, precios y stock mínimo.</p>
            </div>
            <div class="card">
                <h2 class="card-title">Movimientos</h2>
                <p class="card-text">Registra entradas, salidas y ajustes de inventario.</p>
            </div>
            <div class="card">
                <h2 class="card-title">Proveedores</h2>
                <p class="card-text">Administra la información de tus proveedores.</p>
            </div>
            <div class="card">
                <h2 class="card-title">Cotizaciones y Facturas</h2>
                <p class="card-text">Genera cotizaciones y facturas para tus clientes.</p>
            </div>
        </div>
    </div>
</body>
</html>
