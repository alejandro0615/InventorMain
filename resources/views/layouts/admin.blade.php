<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('images/logo.ico') }}" type="image/x-icon">
    <title>@yield('title', 'SUPER ADMIN · Control Center')</title>

    @vite('resources/css/app.css')
    
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --admin-bg: #050505;
            --admin-surface: rgba(20, 20, 25, 0.7);
            --admin-accent: #8b5cf6;
            --admin-accent-glow: rgba(139, 92, 246, 0.5);
            --admin-text: #f8fafc;
            --admin-text-muted: #94a3b8;
            --admin-border: rgba(255, 255, 255, 0.1);

            /* Aliases para compatibilidad con componentes estándar */
            --primary: var(--admin-accent);
            --text-main: var(--admin-text);
            --text-muted: var(--admin-text-muted);
            --bg-body: var(--admin-bg);
            --bg-card: var(--admin-surface);
            --border-color: var(--admin-border);
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: 'Outfit', sans-serif;
            background-color: var(--admin-bg);
            background-image: 
                radial-gradient(circle at 10% 20%, rgba(139, 92, 246, 0.15) 0%, transparent 40%),
                radial-gradient(circle at 90% 80%, rgba(37, 99, 235, 0.1) 0%, transparent 40%);
            color: var(--admin-text);
            min-height: 100vh;
            overflow-x: hidden;
        }

        .admin-layout {
            display: flex;
            min-height: 100vh;
        }

        /* SLEEK SIDEBAR */
        .admin-sidebar {
            width: 80px;
            background: rgba(15, 15, 20, 0.8);
            backdrop-filter: blur(20px);
            border-right: 1px solid var(--admin-border);
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 2rem 0;
            position: sticky;
            top: 0;
            height: 100vh;
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1000;
        }
        
        .admin-sidebar:hover {
            width: 240px;
        }

        .admin-sidebar:hover .nav-text {
            opacity: 1;
            display: block;
        }

        .admin-logo {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, var(--admin-accent), #d946ef);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 1.2rem;
            margin-bottom: 3rem;
            box-shadow: 0 0 20px var(--admin-accent-glow);
        }

        .admin-nav {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            width: 100%;
            padding: 0 15px;
        }

        .admin-nav-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 12px;
            border-radius: 12px;
            color: var(--admin-text-muted);
            text-decoration: none;
            transition: all 0.2s;
            overflow: hidden;
            white-space: nowrap;
        }

        .admin-nav-item i {
            font-size: 1.4rem;
            min-width: 24px;
            text-align: center;
        }

        .nav-text {
            opacity: 0;
            display: none;
            font-weight: 600;
            transition: opacity 0.2s;
        }

        .admin-nav-item:hover, .admin-nav-item.active {
            background: rgba(139, 92, 246, 0.1);
            color: var(--admin-accent);
        }

        .admin-nav-item.active {
            border: 1px solid rgba(139, 92, 246, 0.3);
        }

        /* MAIN CONTENT */
        .admin-main {
            flex: 1;
            padding: 2rem 3rem;
            max-width: 1600px;
            margin: 0 auto;
            width: 100%;
        }

        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 3rem;
        }

        .admin-title-area h1 {
            font-size: 2.5rem;
            font-weight: 800;
            margin: 0;
            background: linear-gradient(to right, #fff, #94a3b8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .admin-title-area p {
            color: var(--admin-text-muted);
            margin: 0.5rem 0 0;
        }

        /* CARDS & GLASSMORPH */
        .admin-card, .card {
            background: var(--admin-surface);
            backdrop-filter: blur(12px);
            border: 1px solid var(--admin-border);
            border-radius: 24px;
            padding: 2rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        /* TABLES */
        .admin-table-container {
            margin-top: 2rem;
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid var(--admin-border);
        }

        .admin-table {
            width: 100%;
            border-collapse: collapse;
            background: rgba(255, 255, 255, 0.02);
        }

        .admin-table th {
            text-align: left;
            padding: 1.2rem;
            background: rgba(255, 255, 255, 0.05);
            color: var(--admin-accent);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            font-weight: 800;
        }

        .admin-table td {
            padding: 1.2rem;
            border-bottom: 1px solid var(--admin-border);
            font-size: 0.95rem;
        }

        .admin-table tr:hover {
            background: rgba(255, 255, 255, 0.03);
        }

        /* BUTTONS */
        .admin-btn-primary, .btn-primary-action {
            background: linear-gradient(135deg, var(--admin-accent), #d946ef);
            color: white;
            padding: 0.8rem 1.5rem;
            border-radius: 12px;
            border: none;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 10px 20px -5px var(--admin-accent-glow);
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .admin-btn-primary:hover, .btn-primary-action:hover {
            transform: translateY(-2px) scale(1.02);
            box-shadow: 0 15px 25px -5px var(--admin-accent-glow);
        }

        /* Aliases para componentes estándar */

        .status-badge {
            padding: 0.4rem 1rem;
            border-radius: 30px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
        }

        .status-active {
            background: rgba(16, 185, 129, 0.1);
            color: #10b981;
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .status-blocked {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        /* MODALS */
        .admin-modal {
            background: rgba(5, 5, 5, 0.8);
            backdrop-filter: blur(10px);
        }

        input {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--admin-border);
            border-radius: 10px;
            padding: 0.8rem;
            color: white;
            width: 100%;
            transition: all 0.2s;
        }

        input:focus {
            outline: none;
            border-color: var(--admin-accent);
            background: rgba(255, 255, 255, 0.08);
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            font-size: 0.85rem;
            color: var(--admin-text-muted);
            font-weight: 600;
        }

    </style>
</head>
<body>
    <div class="admin-layout">
        <aside class="admin-sidebar">
            <div class="admin-logo">M</div>
            
            <nav class="admin-nav">
                <a href="{{ route('admin.users.index') }}" class="admin-nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i>🛡️</i>
                    <span class="nav-text">Gestión Usuarios</span>
                </a>
                
                <div style="margin-top: auto; width: 100%;">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="admin-nav-item" style="background: none; border: none; width: 100%; cursor: pointer; text-align: left;">
                            <i>🚪</i>
                            <span class="nav-text">Cerrar Sesión</span>
                        </button>
                    </form>
                </div>
            </nav>
        </aside>

        <main class="admin-main">
            <header class="admin-header">
                <div class="admin-title-area">
                    <h1>@yield('header_title', 'Master Control')</h1>
                    <p>@yield('header_subtitle', 'Panel de Administración Centralizado')</p>
                </div>

                <div style="display: flex; align-items: center; gap: 1.5rem;">
                    <div style="text-align: right;">
                        <div style="font-weight: 800; font-size: 1rem;">{{ auth()->user()->name }}</div>
                        <div style="font-size: 0.75rem; color: var(--admin-accent); font-weight: 700;">SUPER ADMIN</div>
                    </div>
                    <div style="width: 50px; height: 50px; border-radius: 15px; background: var(--admin-surface); border: 1px solid var(--admin-border); display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                        👑
                    </div>
                </div>
            </header>

            <div class="admin-content-fade-in">
                @yield('content')
            </div>
        </main>
    </div>

    @stack('scripts')
</body>
</html>
