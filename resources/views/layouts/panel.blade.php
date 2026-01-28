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
        /* ===== GLOBAL ===== */
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Inter', system-ui, sans-serif;
            background: radial-gradient(circle at top, #e0f2fe, #f8fafc);
            color: #1f2937;
        }

        input,
        select {
            background: #fff;
            color: #111827;
            border: 1px solid #d1d5db;
            transition: all 0.25s ease;
        }

        .input-cantidad,
        .input-precio,
        .input-iva-linea {
            transition: all 0.25s ease;
        }

        /* ===== DARK MODE INPUTS ===== */
        body.dark input,
        body.dark select,
        body.dark .input-cantidad,
        body.dark .input-precio,
        body.dark .input-iva-linea {
            background: rgba(15, 23, 42, 0.6);
            color: #e5e7eb;
            border: 1px solid rgba(255, 255, 255, 0.18);
        }

        /* ===== LAYOUT ===== */
        .layout {
            display: grid;
            grid-template-columns: 240px 1fr;
            min-height: 100vh;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            background: linear-gradient(180deg, #fff, #f1f5f9);
            border-right: 1px solid #e5e7eb;
            padding: 1.8rem 1.2rem;
        }

        .sidebar-title {
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 2rem;
            background: linear-gradient(90deg, #3b82f6, #22c55e);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .nav-title {
            font-size: .7rem;
            text-transform: uppercase;
            letter-spacing: .15em;
            color: #94a3b8;
            margin-bottom: .6rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: .6rem;
            padding: .55rem .8rem;
            border-radius: .6rem;
            font-size: .9rem;
            color: #334155;
            text-decoration: none;
            transition: all .25s ease;
        }

        .nav-link:hover {
            background: #e0f2fe;
            color: #1d4ed8;
            transform: translateX(3px);
        }

        /* ===== MAIN ===== */
        .main {
            padding: 2rem;
            background: linear-gradient(180deg, #f8fafc, #f1f5f9);
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .top-title {
            font-size: 1.6rem;
            font-weight: 700;
            background: linear-gradient(90deg, #2563eb, #22c55e);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: .6rem;
            font-size: .85rem;
            color: #475569;
        }

        /* ===== BUTTONS ===== */
        .btn-tour,
        .logout-btn,
        .theme-btn,
        .btn-pdf,
        .btn-download,
        .btn-print,
        .btn-delete {
            border-radius: .6rem;
            padding: .45rem .9rem;
            cursor: pointer;
            font-weight: 600;
            transition: all .25s ease;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            text-decoration: none;
        }

        .btn-tour {
            background: linear-gradient(90deg, #60a5fa, #22c55e);
            color: #fff;
            margin-left: .7rem;
        }

        .btn-tour:hover {
            filter: brightness(1.08);
        }

        .logout-btn {
            background: linear-gradient(90deg, #fb7185, #f43f5e);
            color: #fff;
            margin-left: .6rem;
        }

        .theme-btn {
            background: #fff;
            border: 1px solid #e5e7eb;
            padding: .45rem .6rem;
            font-size: 1rem;
            margin-left: .6rem;
        }

        .theme-btn:hover {
            background: #f1f5f9;
        }

        /* BUTTONS COLOR VARIANTS */
        .btn-pdf {
            background: #ef4444;
            color: #fff;
        }

        .btn-pdf:hover {
            background: #dc2626;
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, .1);
        }

        .btn-download {
            background: #10b981;
            color: #fff;
        }

        .btn-download:hover {
            background: #059669;
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, .1);
        }

        .btn-print {
            background: #3b82f6;
            color: #fff;
        }

        .btn-print:hover {
            background: #2563eb;
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, .1);
        }

        .btn-delete {
            background: #f97316;
            color: #fff;
        }

        .btn-delete:hover {
            background: #ea580c;
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, .1);
        }

        .btn-pdf i,
        .btn-download i,
        .btn-print i,
        .btn-delete i {
            font-size: .9rem;
        }

        /* ===== CARDS ===== */
        .card {
            background: #fff;
            border-radius: 1rem;
            border: 1px solid #e5e7eb;
            box-shadow: 0 12px 25px rgba(15, 23, 42, 0.08);
            padding: 1.8rem;
        }

        .card-title {
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: #1e293b;
        }

        .card-subtitle {
            font-size: .85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: #9ab5ed;
            margin-top: 1rem;
            margin-bottom: .4rem;
        }

        .section-list {
            list-style: disc;
            padding-left: 1.3rem;
            margin: 0 0 .8rem 0;
            line-height: 1.55;
            font-size: .88rem;
            color: #68b691;
        }

        /* ===== TABLES ===== */
        .table-wrapper {
            border-radius: .75rem;
            overflow: hidden;
            border: 1px solid #e5e7eb;
            background: #fff;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: .85rem;
            color: #334155;
        }

        th,
        td {
            padding: .55rem .85rem;
            vertical-align: middle;
        }

        thead {
            background: #f8fafc;
        }

        th {
            color: #64748b;
            font-weight: 500;
            border-bottom: 1px solid #e5e7eb;
        }

        tbody tr:hover {
            background: #f0f9ff;
        }

        tr {
            height: 48px;
        }

        /* ===== TAGS ===== */
        .tag {
            padding: .2rem .6rem;
            border-radius: 999px;
            font-size: .7rem;
            background: #e5e7eb;
            color: #334155;
        }

        .tag-green {
            background: #dcfce7;
            color: #166534;
        }

        .tag-red {
            background: #fee2e2;
            color: #991b1b;
        }

        /* ===== ACTIONS ===== */
        .actions {
            display: inline-flex;
            gap: .4rem;
            align-items: center;
            white-space: nowrap;
        }

        .actions form {
            display: inline-block;
            margin: 0;
        }

        /* ===== USER PILL ===== */
        .user-name-pill {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            padding: .25rem .7rem;
            border-radius: 999px;
            background: #e0f2fe;
            color: #0f172a;
            font-size: .8rem;
            font-weight: 600;
        }

        /* ===== PROFILE GRID ===== */
        .profile-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 1.2rem;
        }

        .profile-box {
            background: rgba(241, 245, 249, 0.6);
            border-radius: .9rem;
            padding: 1.2rem;
        }

        .profile-title {
            font-size: .8rem;
            text-transform: uppercase;
            letter-spacing: .12em;
            color: #64748b;
            margin-bottom: 1rem;
        }

        .profile-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: .45rem 0;
            border-bottom: 1px dashed #e5e7eb;
        }

        .profile-item:last-child {
            border-bottom: none;
        }

        .profile-item span {
            font-size: .75rem;
            color: #64748b;
        }

        .profile-item strong {
            font-size: .85rem;
            color: #0f172a;
        }

        /* ===== LOADER ===== */
        #globalLoader {
            transition: opacity .3s ease;
        }

        /* ===== DARK MODE ===== */
        body.dark {
            background: radial-gradient(circle at top, #020617, #020617);
            color: #e5e7eb;
        }

        body.dark .sidebar {
            background: linear-gradient(180deg, #020617, #020617cc);
            border-right: 1px solid rgba(56, 189, 248, .18);
        }

        body.dark .sidebar-title,
        body.dark .top-title {
            background: linear-gradient(90deg, #60a5fa, #22c55e);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        body.dark .nav-title {
            color: #64748b;
        }

        body.dark .nav-link {
            color: #c7d2fe;
        }

        body.dark .nav-link:hover {
            background: rgba(59, 130, 246, .18);
            color: #e0f2fe;
            box-shadow: inset 3px 0 0 #22c55e;
        }

        body.dark .main {
            background: linear-gradient(180deg, #020617, #020617cc);
        }

        body.dark .user-info {
            color: #94a3b8;
        }

        body.dark .btn-tour {
            background: linear-gradient(90deg, #3b82f6, #22c55e);
        }

        body.dark .logout-btn {
            background: linear-gradient(90deg, #fb7185, #f43f5e);
        }

        body.dark .theme-btn {
            background: rgba(255, 255, 255, .06);
            border: 1px solid rgba(255, 255, 255, .12);
            color: #e5e7eb;
        }

        body.dark .card {
            background: linear-gradient(180deg, rgba(15, 23, 42, .92), rgba(2, 6, 23, .88));
            border: 1px solid rgba(59, 130, 246, .22);
            box-shadow: 0 25px 50px rgba(2, 6, 23, .9), inset 0 0 0 1px rgba(34, 197, 94, .08);
        }

        body.dark .card-title {
            color: #e0f2fe;
        }

        body.dark thead {
            background: rgba(15, 23, 42, .8);
        }

        body.dark th {
            color: #93c5fd;
            border-bottom: 1px solid rgba(59, 130, 246, .18);
        }

        body.dark td {
            color: #c7d2fe;
            border-bottom: 1px solid rgba(255, 255, 255, .08);
        }

        body.dark tbody tr:hover {
            background: rgba(59, 130, 246, .12);
        }

        body.dark .tag {
            background: rgba(148, 163, 184, .15);
            color: #c7d2fe;
        }

        body.dark .tag-green {
            background: rgba(34, 197, 94, .18);
            color: #86efac;
        }

        body.dark .tag-red {
            background: rgba(248, 113, 113, .18);
            color: #fecaca;
        }

        body.dark .user-name-pill {
            background: rgba(37, 99, 235, .22);
            color: #e0f2fe;
            box-shadow: 0 0 0 1px rgba(59, 130, 246, .4);
        }

        body.dark .profile-box {
            background: rgba(15, 23, 42, .55);
        }

        body.dark .profile-title {
            color: #93c5fd;
        }

        body.dark .profile-item span {
            color: #94a3b8;
        }

        body.dark .profile-item strong {
            color: #e0f2fe;
        }

        body.dark .profile-item {
            border-bottom: 1px dashed rgba(255, 255, 255, .12);
        }

        body.dark .table-wrapper {
            background: linear-gradient(180deg, rgba(15, 23, 42, .85), rgba(2, 6, 23, .8));
            border: 1px solid rgba(59, 130, 246, .25);
        }
    </style>


    @stack('styles')
</head>

<body>
    <div id="globalLoader"
        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-40 backdrop-blur-sm hidden opacity-0"
        style="z-index:99999">
    </div>
    <div class="layout">
        <aside class="sidebar">
            <div class="sidebar-title" id="tour-logo">
                <div
                    style="
        display:flex;
        flex-direction:column;
        align-items:center;
        gap:0.35rem;
    ">
                    <img src="{{ asset('images/imagen.png') }}" alt="Logo inventario"
                        style="
                width:100px;
                height:100px;
                object-fit:cover;
                border-radius:1rem;
                box-shadow:0 0 0 1px rgba(15,23,42,0.5),
                           0 8px 16px rgba(15,23,42,0.55);
            ">
                    <span
                        style="
            font-size:0.9rem;
            font-weight:600;
            letter-spacing:0.03em;
            text-transform:uppercase;
            color:#e5e7eb;
            white-space:nowrap;
        ">
                        Inventario · Panel
                    </span>
                </div>
                </button>

            </div>


            <div class="nav-section" id="tour-modulos">
                <div class="nav-title">Módulos</div>

                <a href="{{ route('principal') }}" class="nav-link" id="tour-principal"
                    @click.prevent="loadView('{{ route('principal') }}')">
                    Ventana principal
                </a>

                <a href="{{ route('productos.index') }}" class="nav-link" id="tour-productos"
                    @click.prevent="loadView('{{ route('productos.index') }}')">
                    Productos
                </a>

                <a href="{{ route('proveedores.index') }}" class="nav-link" id="tour-proveedores"
                    @click.prevent="loadView('{{ route('proveedores.index') }}')">
                    Proveedores o persona Natural
                </a>
                <a href="{{ route('movimientos.index') }}" class="nav-link" id="tour-movimientos"
                    @click.prevent="loadView('{{ route('movimientos.index') }}')">
                    Movimientos
                </a>
                <a href="{{ route('cotizacion.index') }}" class="nav-link" id="tour-cotizaciones"
                    @click.prevent="loadView('{{ route('cotizacion.index') }}')">
                    Cotizaciones
                </a>
            </div>
        </aside>

        <main class="main" id="tour-main">
            <div class="top-bar">
                <div class="top-title">
                    @yield('page_title', 'Bienvenido al panel principal')
                </div>

                <div class="user-info">

                    <a href="{{ route('perfil') }}" class="user-name-pill"
                        @click.prevent="loadView('{{ route('perfil') }}')">
                        👤 {{ auth()->user()->email ?? 'Usuario' }}
                    </a>

                    <button type="button" onclick="window.startTour()" class="btn-tour">
                        INICIAR TOUR
                    </button>

                    <button id="themeToggle" class="theme-btn">
                        🌙
                    </button>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="logout-btn">
                            Cerrar sesión
                        </button>
                    </form>

                </div>

            </div>

            <!-- CONTENIDO DINÁMICO -->
            <div id="dynamic-content">
                @yield('content')
            </div>
        </main>
    </div>

    <script>
        window.loadView = async function(url, push = true) {
            const loader = document.getElementById('globalLoader');
            const content = document.getElementById('dynamic-content');

            loader.classList.remove('hidden', 'opacity-0');

            try {
                const res = await fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const html = await res.text();
                await new Promise(r => setTimeout(r, 1200));

                // 🔑 Solo reemplaza el contenido, NO el título ni nada más
                content.innerHTML = html;

                if (push) history.pushState({
                    url
                }, '', url);
            } catch (e) {
                console.error(e);
                content.innerHTML = '<div class="card p-4 text-red-500">Error cargando vista</div>';
            } finally {
                loader.classList.add('opacity-0');
                setTimeout(() => loader.classList.add('hidden'), 300);
            }
        };
    </script>

    <script>
        const toggleBtn = document.getElementById('themeToggle');
        const body = document.body;

        // Cargar tema guardado
        if (localStorage.getItem('theme') === 'dark') {
            body.classList.add('dark');
            toggleBtn.textContent = '☀️';
        }

        toggleBtn.addEventListener('click', () => {
            body.classList.toggle('dark');

            const isDark = body.classList.contains('dark');
            toggleBtn.textContent = isDark ? '☀️' : '🌙';
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
        });
    </script>
    <script>
        window.addEventListener('popstate', (e) => {
            if (e.state?.url) {
                window.loadView(e.state.url, false)
            }
        })
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const driver = window.driver?.js?.driver;
            if (!driver) return;

            const driverObj = driver({
                showProgress: true,
                nextBtnText: 'Siguiente',
                prevBtnText: 'Atrás',
                doneBtnText: 'Terminar',
                steps: [{
                        element: '#tour-logo',
                        popover: {
                            title: 'Bienvenido al panel',
                            description: 'Este es el panel principal de tu sistema de inventario.',
                            position: 'right'
                        }
                    },
                    {
                        element: '#tour-principal',
                        popover: {
                            title: 'Ventana principal',
                            description: 'Desde aquí podrás regresar a la ventana principal.',
                            position: 'right'
                        }
                    },
                    {
                        element: '#tour-productos',
                        popover: {
                            title: 'Productos',
                            description: 'Aquí gestionarás el catálogo de productos y sus stocks.',
                            position: 'right'
                        }
                    },
                    {
                        element: '#tour-proveedores',
                        popover: {
                            title: 'Proveedores',
                            description: 'Accede al listado y la información de tus proveedores.',
                            position: 'right'
                        }
                    },
                    {
                        element: '#tour-movimientos',
                        popover: {
                            title: 'Movimientos',
                            description: 'Desde aquí registrarás entradas y salidas de inventario.',
                            position: 'right'
                        }
                    },
                    {
                        element: '#tour-cotizaciones',
                        popover: {
                            title: 'Cotizaciones',
                            description: 'Crea y administra cotizaciones para tus clientes.',
                            position: 'right'
                        }
                    },
                    {
                        element: '#tour-main',
                        popover: {
                            title: 'Zona de trabajo',
                            description: 'Aquí verás tablas de productos, movimientos y más.',
                            position: 'top'
                        }
                    }
                ]
            });

            window.startTour = function() {
                driverObj.drive();
            };
        });
    </script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @stack('scripts')
    </div>

</body>

</html>
