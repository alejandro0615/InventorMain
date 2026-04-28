<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Cotizacion;
use App\Models\Salida;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->email === 'torresgiraldojhon1224@outlook.es') {
            return redirect()->route('admin.users.index');
        }

        $localId = $user->local_id;
        $now = Carbon::now();

        // 1. Estadísticas rápidas
        $totalProductos = Producto::where('local_id', $localId)->count();
        $stockBajo = Producto::where('local_id', $localId)->where('stock_actual', '<=', 5)->count();
        
        // En Salida usamos user_id porque no tiene local_id
        $ventasMes = Salida::where('user_id', $user->id)
            ->whereMonth('fecha', $now->month)
            ->whereYear('fecha', $now->year)
            ->sum('total');

        // 2. Actividad reciente
        $ultimasVentas = Salida::where('user_id', $user->id)
            ->with('cliente')
            ->latest()
            ->take(5)
            ->get();

        $ultimasCotizaciones = Cotizacion::where('local_id', $localId)
            ->with('cliente')
            ->latest()
            ->take(5)
            ->get();

        $stats = [
            'totalProductos' => $totalProductos,
            'stockBajo'      => $stockBajo,
            'ventasMes'      => $ventasMes,
            'ultimasVentas'  => $ultimasVentas,
            'ultimasCotizaciones' => $ultimasCotizaciones,
        ];

        if ($request->ajax()) {
            return view('principal._content', $stats);
        }

        return view('principal.principal', $stats);
    }
}
