<div class="dashboard-wrapper" style="animation: fadeIn 0.5s ease;">
    <div class="page-header" style="border:none; margin-bottom: 2rem;">
        <h1 style="font-size: 1.6rem; font-weight: 800; margin: 0; color: var(--primary);">Bienvenido, {{ Auth::user()->name }} 👋</h1>
        <p class="text-muted" style="font-size: 0.85rem; margin-top: 0.25rem;">Resumen operativo del negocio para hoy {{ now()->format('d/m/Y') }}</p>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.5rem; margin-bottom: 2.5rem;">
        <div class="card" style="display:flex; align-items:center; gap:1rem; border-left: 4px solid var(--primary);">
            <div style="font-size: 1.5rem;">📦</div>
            <div>
                <div class="text-muted" style="font-size: 0.65rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em;">Productos</div>
                <div style="font-size: 1.3rem; font-weight: 800;">{{ $totalProductos }}</div>
            </div>
        </div>

        <div class="card" style="display:flex; align-items:center; gap:1rem; border-left: 4px solid #ef4444;">
            <div style="font-size: 1.5rem;">⚠️</div>
            <div>
                <div class="text-muted" style="font-size: 0.65rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em;">Bajo Stock</div>
                <div style="font-size: 1.3rem; font-weight: 800; color: #ef4444;">{{ $stockBajo }}</div>
            </div>
        </div>

        <div class="card" style="display:flex; align-items:center; gap:1rem; border-left: 4px solid #10b981;">
            <div style="font-size: 1.5rem;">💰</div>
            <div>
                <div class="text-muted" style="font-size: 0.65rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em;">Ventas Mes</div>
                <div style="font-size: 1.3rem; font-weight: 800; color: #10b981;">$ {{ number_format($ventasMes, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
        <div class="card">
            <h3 class="card-title" style="font-size: 0.9rem; font-weight: 800; margin-bottom: 1.25rem; display:flex; align-items:center; gap:0.5rem;">
                <span>📊</span> Últimos Movimientos
            </h3>
            <div class="table-wrapper">
                <table style="font-size: 0.8rem;">
                    <thead>
                        <tr>
                            <th>Cliente</th>
                            <th class="text-right">Total</th>
                            <th class="text-center">Ver</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ultimasVentas as $venta)
                        <tr>
                            <td style="font-weight: 600;">{{ $venta->cliente->nombre }}</td>
                            <td class="text-right bold" style="color: var(--primary);">$ {{ number_format($venta->total, 0, ',', '.') }}</td>
                            <td class="text-center">
                                <a href="{{ route('movimientos.index') }}" style="color: var(--primary); text-decoration:none; font-weight: 800;">>></a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center text-muted">Sin registros.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card">
            <h3 class="card-title" style="font-size: 0.9rem; font-weight: 800; margin-bottom: 1.25rem; display:flex; align-items:center; gap:0.5rem;">
                <span>📝</span> Cotizaciones Recientes
            </h3>
            <div class="table-wrapper">
                <table style="font-size: 0.8rem;">
                    <thead>
                        <tr>
                            <th>Cliente</th>
                            <th class="text-right">Total</th>
                            <th class="text-center">PDF</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ultimasCotizaciones as $cot)
                        <tr>
                            <td style="font-weight: 600;">{{ $cot->cliente->nombre }}</td>
                            <td class="text-right bold" style="color: var(--primary);">$ {{ number_format($cot->total, 0, ',', '.') }}</td>
                            <td class="text-center">
                                <a href="{{ route('cotizaciones.pdf', $cot->id) }}" target="_blank" style="color: #ef4444; text-decoration:none; font-weight: 800;">📄</a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center text-muted">Sin registros.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(5px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
