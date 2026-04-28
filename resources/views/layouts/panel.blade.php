<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/driver.js@latest/dist/driver.css" />
    <link rel="icon" href="{{ asset('images/logo.ico') }}" type="image/x-icon">
    <script src="https://cdn.jsdelivr.net/npm/driver.js@latest/dist/driver.js.iife.js"></script>
    <title>@yield('title', 'PANEL PRINCIPAL · Inventario')</title>

    @vite('resources/css/app.css')

    <style>
        /* ===== SISTEMA DE DISEÑO EQUILIBRADO Y LEGIBLE ===== */
        :root {
            --primary: #2563eb;
            --primary-glow: rgba(37, 99, 235, 0.2);
            --bg-body: #f1f5f9;
            --bg-card: #ffffff;
            --bg-sidebar: #0f172a;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        body.dark {
            --bg-body: #020617;
            --bg-card: #0f172a;
            --bg-sidebar: #000000;
            --text-main: #f1f5f9;
            --text-muted: #94a3b8;
            --border-color: rgba(51, 65, 85, 0.5);
            --card-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.5);
            --primary: #3b82f6;
            --primary-glow: rgba(59, 130, 246, 0.3);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Inter', system-ui, sans-serif;
            background-color: var(--bg-body);
            color: var(--text-main);
            transition: all 0.3s ease;
            overflow-x: hidden;
            font-size: 0.95rem;
            /* Un poco más grande para legibilidad general */
        }

        .app-container {
            display: flex;
            width: 100vw;
            min-height: 100vh;
        }

        /* SIDEBAR */
        .sidebar {
            background-color: var(--bg-sidebar);
            width: 240px;
            height: 100vh;
            position: sticky;
            top: 0;
            padding: 2rem 1rem;
            color: white;
            flex-shrink: 0;
            z-index: 100;
        }

        .main-content {
            flex-grow: 1;
            padding: 1.5rem 2rem;
            display: flex;
            flex-direction: column;
            gap: 1.2rem;
            width: calc(100vw - 240px);
        }

        .top-bar {
            background-color: var(--bg-card);
            border: 1px solid var(--border-color);
            padding: 0.75rem 1.5rem;
            border-radius: 0.75rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: var(--card-shadow);
        }

        .top-title {
            font-size: 1.2rem;
            font-weight: 800;
            text-transform: uppercase;
        }

        .card {
            background-color: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 0.75rem;
            padding: 1.5rem;
            box-shadow: var(--card-shadow);
            width: 100%;
        }

        /* TABLAS CON FUENTE MEJORADA */
        .table-wrapper {
            background-color: var(--bg-card);
            border-radius: 0.5rem;
            border: 1px solid var(--border-color);
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background-color: rgba(148, 163, 184, 0.05);
            color: var(--text-muted);
            font-size: 0.75rem;
            /* Subido de 0.65rem */
            font-weight: 800;
            text-transform: uppercase;
            padding: 1rem;
            border-bottom: 1px solid var(--border-color);
            text-align: left;
        }

        td {
            padding: 1rem;
            border-bottom: 1px solid var(--border-color);
            font-size: 0.95rem;
            /* Subido de 0.8rem para mayor claridad */
        }

        tr:hover td {
            background-color: rgba(148, 163, 184, 0.03);
        }

        /* BOTONES */
        .btn-primary-action,
        .btn-print,
        .btn-download,
        .btn-delete,
        .logout-btn {
            padding: 0.6rem 1.3rem;
            border-radius: 0.4rem;
            font-weight: 800;
            font-size: 0.8rem;
            border: none;
            cursor: pointer;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            color: white;
            text-decoration: none;
        }

        .btn-primary-action,
        .btn-print {
            background: var(--primary);
            box-shadow: 0 4px 12px var(--primary-glow);
        }

        .btn-primary-action:hover {
            transform: translateY(-1px);
            filter: brightness(1.1);
        }

        .btn-download {
            background: #10b981;
        }

        .btn-delete {
            background: #ef4444;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            color: #94a3b8;
            font-size: 0.9rem;
            text-decoration: none;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .nav-link:hover {
            color: white;
            background: rgba(255, 255, 255, 0.05);
        }

        .nav-link.active {
            background-color: var(--primary);
            color: white;
        }

        .user-profile-link {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            background: rgba(148, 163, 184, 0.05);
            border: 1px solid var(--border-color);
            text-decoration: none;
            color: inherit;
        }
    </style>

    @stack('styles')
</head>

<body>
    <div id="globalLoader"
        class="fixed inset-0 items-center justify-center bg-black bg-opacity-40 backdrop-blur-sm hidden opacity-0"
        style="z-index:99999">
        <div class="animate-spin rounded-full h-8 w-8 border-4 border-white border-t-transparent"></div>
    </div>

    <div class="app-container">
        <aside class="sidebar">
            <div class="text-center" style="margin-bottom: 2.5rem;">
                <img src="{{ asset('images/imagen.png') }}" alt="Logo"
                    style="width: 70px; height: 70px; border-radius: 1rem; margin-bottom: 1rem;">
                <div style="font-weight: 800; font-size: 1.1rem; letter-spacing: 0.05em; color: white;">GESTIÓN PRO
                </div>
                <div style="font-size: 0.6rem; color: var(--primary); font-weight: 700; text-transform: uppercase;">
                    Sistema Enterprise</div>
            </div>

            <nav>
                @if(auth()->user()->email === 'torresgiraldojhon1224@outlook.es')
                    <div
                        style="margin: 1rem 1rem 0.5rem; font-size: 0.65rem; font-weight: 800; color: var(--primary); text-transform: uppercase; letter-spacing: 0.1em;">
                        ADMINISTRACIÓN</div>
                    <a href="{{ route('admin.users.index') }}"
                        class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">🛡️ Usuarios</a>
                @else
                    <a href="{{ route('principal') }}"
                        class="nav-link {{ request()->routeIs('principal') ? 'active' : '' }}">🏠 Inicio</a>
                    <a href="{{ route('productos.index') }}"
                        class="nav-link {{ request()->routeIs('productos.*') ? 'active' : '' }}">📦 Productos</a>
                    <a href="{{ route('proveedores.index') }}"
                        class="nav-link {{ request()->routeIs('proveedores.*') ? 'active' : '' }}">🤝 Proveedores</a>
                    <a href="{{ route('movimientos.index') }}"
                        class="nav-link {{ request()->routeIs('movimientos.*') ? 'active' : '' }}">📊 Movimientos</a>
                    <a href="{{ route('cotizaciones.index') }}"
                        class="nav-link {{ request()->routeIs('cotizaciones.*') ? 'active' : '' }}">📝 Cotizaciones</a>
                    <a href="{{ route('historial.index') }}"
                        class="nav-link {{ request()->routeIs('historial.*') ? 'active' : '' }}">🕒 Historial</a>
                    <a href="{{ route('perfil') }}" class="nav-link {{ request()->routeIs('perfil') ? 'active' : '' }}">👤
                        Perfil</a>
                @endif
            </nav>
        </aside>

        <main class="main-content">
            <header class="top-bar">
                <div class="top-title">@yield('page_title', 'Dashboard')</div>

                <div style="display: flex; align-items: center; gap: 1.2rem;">
                    <button id="startTour" style="border:none; background:none; font-size:1.3rem; cursor:pointer;"
                        title="Guía de usuario">❓Ayuda </button>
                    <button id="themeToggle" style="border:none; background:none; font-size:1.3rem; cursor:pointer;"
                        title="Cambiar tema">🌙</button>

                    <a href="{{ route('perfil') }}" class="user-profile-link">
                        <div
                            style="width: 30px; height: 30px; background: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 0.9rem;">
                            {{ substr(auth()->user()->name ?? auth()->user()->email, 0, 1) }}
                        </div>
                        <span
                            style="font-size: 0.9rem; font-weight: 700; color: var(--text-main);">{{ auth()->user()->name ?? auth()->user()->email }}</span>
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="logout-btn" style="background:#ef4444;">Salir</button>
                    </form>
                </div>
            </header>

            <div id="dynamic-content" style="width: 100%;">
                @if (session('success'))
                    <div
                        style="background: rgba(16, 185, 129, 0.1); color: #10b981; padding: 1rem 2rem; border-radius: 0.8rem; border: 1px solid rgba(16, 185, 129, 0.2); margin-bottom: 1.5rem; font-weight: 700; font-size: 0.9rem;">
                        ✅ {{ session('success') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <script>
        const themeToggle = document.getElementById('themeToggle');
        const body = document.body;

        if (localStorage.getItem('theme') === 'dark') {
            body.classList.add('dark');
            themeToggle.textContent = '☀️';
        }

        themeToggle.addEventListener('click', () => {
            body.classList.toggle('dark');
            const isDark = body.classList.contains('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            themeToggle.textContent = isDark ? '☀️' : '🌙';
        });

        // --- CONFIGURACIÓN DE DRIVER.JS ---
        const driver = window.driver.js.driver;

        const driverObj = driver({
            showProgress: true,
            nextBtnText: 'Siguiente —>',
            prevBtnText: '<— Anterior',
            doneBtnText: 'Finalizar',
            steps: [
                // DASHBOARD
                { element: '#tour-productos', popover: { title: '📦 Productos', description: 'Total de productos registrados.', side: "bottom", align: 'start' } },
                { element: '#tour-stock-bajo', popover: { title: '⚠️ Alerta de Stock', description: 'Productos que necesitan reabastecimiento.', side: "bottom", align: 'start' } },
                { element: '#tour-ventas', popover: { title: '💰 Ventas', description: 'Resumen financiero del mes actual.', side: "bottom", align: 'start' } },
                { element: '#tour-movimientos', popover: { title: '📊 Actividad', description: 'Historial de los últimos movimientos realizados.', side: "top", align: 'start' } },

                // PRODUCTOS
                { element: '#tour-search', popover: { title: '🔍 Buscador', description: 'Encuentra productos por nombre o código.', side: "bottom", align: 'start' } },
                { element: '#tour-filters', popover: { title: '🧪 Filtros', description: 'Visualiza rápidamente solo lo que te interesa.', side: "bottom", align: 'start' } },

                // MOVIMIENTOS
                { element: '#tour-filtros-movimientos', popover: { title: '📅 Filtros de Salida', description: 'Filtra tus ventas por cliente o fecha específica.', side: "bottom", align: 'start' } },

                // COTIZACIONES
                { element: '#tour-config-cotizacion', popover: { title: '👤 Cliente y Fecha', description: 'Define quién recibirá la cotización y cuándo.', side: "bottom", align: 'start' } },
                { element: '#tour-buscador-cotizacion', popover: { title: '🛒 Selección de Ítems', description: 'Añade productos a la cotización escribiendo su nombre.', side: "bottom", align: 'start' } },

                // PROVEEDORES
                { element: '#tour-nuevo-proveedor', popover: { title: '➕ Registro', description: 'Agrega un nuevo contacto a tu base de datos comercial.', side: "bottom", align: 'start' } },
            ]
        });

        document.getElementById('startTour').addEventListener('click', () => {
            // Filtrar solo los pasos cuyos elementos existen en la vista actual
            const activeSteps = [
                // DASHBOARD
                { element: '#tour-productos', popover: { title: ' Productos', description: 'Total de productos registrados.', side: "bottom", align: 'start' } },
                { element: '#tour-stock-bajo', popover: { title: '⚠️ Alerta de Stock', description: 'Productos que necesitan reabastecimiento.', side: "bottom", align: 'start' } },
                { element: '#tour-ventas', popover: { title: ' Ventas', description: 'Resumen financiero del mes actual.', side: "bottom", align: 'start' } },
                { element: '#tour-movimientos', popover: { title: ' Actividad', description: 'Historial de los últimos movimientos realizados.', side: "top", align: 'start' } },
                { element: '#tour-cotizaciones', popover: { title: ' Cotizaciones Recientes', description: 'Historial de las últimas cotizaciones realizadas.', side: "top", align: 'start' } },

                // PRODUCTOS
                { element: '#tourt-titulo', popover: { title: ' Inventario de Productos', description: 'Administración de existencias y precios.', side: 'bottom', align: 'start' } },
                { element: '#tour-search', popover: { title: '🔍 Buscador', description: 'Encuentra productos por nombre o código.', side: "bottom", align: 'start' } },
                { element: '#tour-filters', popover: { title: '🧪 Filtros', description: 'Visualiza rápidamente solo lo que te interesa.', side: "bottom", align: 'start' } },
                { element: '#tourt-newProduct', popover: { title: '➕ Nuevo Producto', description: 'Agrega un nuevo producto a tu inventario.', side: "bottom", align: 'start' } },
                { element: '#tourt-table', popover: { title: 'tabla', description: 'Aca veras los productos que tengas en tu inventario.', side: "bottom", align: 'start' } },

                // PROVEEDORES
                { element: '#tourt-directorio', popover: { title: 'Directorio', description: 'Aca podras tener organizados tus proveedores y clientes para las facturas.', side: "bottom", align: 'start' } },
                { element: '#tour-nuevo-proveedor', popover: { title: '➕ Registro', description: 'Agrega un nuevo contacto a tu base de datos comercial.', side: "bottom", align: 'start' } },
                { element: '#tourt-tableProve', popover: { title: ' tabla', description: 'Aca veras los productos que tengas en tu inventario.', side: "bottom", align: 'start' } },

                // MOVIMIENTOS
                { element: '#tourt-M', popover: { title: 'Movimientos', description: 'Aca podras tener organizados tus proveedores y clientes para las facturas.', side: "bottom", align: 'start' } },
                { element: '#tour-filtros-movimientos', popover: { title: '📅 Filtros de Salida', description: 'Filtra tus ventas por cliente o fecha específica.', side: "bottom", align: 'start' } },
                { element: '#tourt-fecha', popover: { title: ' fecha', description: 'Aca podras tener organizados tus proveedores y clientes para las facturas.', side: "bottom", align: 'start' } },
                { element: '#tour-tablaM', popover: { title: ' tabla', description: 'Aca veras los movimientos que hagas junto con las facturas.', side: "bottom", align: 'start' } },

                // COTIZACIONES
                { element: '#tourt-cotizaciones', popover: { title: 'Cotizaciones', description: 'Aca podras hacer tus cotizaciones.', side: "bottom", align: 'start' } },
                { element: '#tour-config-cotizacion', popover: { title: '👤 Cliente y Fecha', description: 'Define quién recibirá la cotización y cuándo.', side: "bottom", align: 'start' } },
                { element: '#tour-buscador-cotizacion', popover: { title: ' Selección de Ítems', description: 'Añade productos a la cotización escribiendo su nombre.', side: "bottom", align: 'start' } },
                { element: '#tourt-tablaC', popover: { title: ' Tabla', description: 'Aca veras los productos agregados y podras generar pdf para enviarlo a tus clientes.', side: "bottom", align: 'start' } },
                { element: '#tourt-finC', popover: { title: ' totales', description: 'Aca veras los totales de la cotizacion.', side: "bottom", align: 'start' } },
                { element: '#tablaHistorial', popover: { title: 'Historial Reciente', description: 'Lista de las últimas cotizaciones realizadas.', side: "bottom", align: 'start' } },


                //HISTORIAL
                { element: '#tourt-historial', popover: { title: 'Historial de ventas', description: 'Aca veras el historial de ventas realizadas.', side: "bottom", align: 'start' } },
                { element: '#tourt-filtros', popover: { title: 'filtros', description: 'Aca podras filtrar las ventas por fecha.', side: "bottom", align: 'start' } },
                { element: '#tourt-tablaH', popover: { title: 'tabla', description: 'Aca veras el historial de ventas realizadas.', side: "bottom", align: 'start' } },
                
            ].filter(step => document.querySelector(step.element) !== null);

            if (activeSteps.length > 0) {
                const dynamicDriver = driver({
                    showProgress: true,
                    nextBtnText: 'Siguiente —>',
                    prevBtnText: '<— Anterior',
                    doneBtnText: 'Finalizar',
                    steps: activeSteps
                });
                dynamicDriver.drive();
            }
        });

        // Auto-iniciar tour si es la primera vez
        if (!localStorage.getItem('tour_completed')) {
            setTimeout(() => {
                driverObj.drive();
                localStorage.setItem('tour_completed', 'true');
            }, 1000);
        }
    </script>
</body>

</html>