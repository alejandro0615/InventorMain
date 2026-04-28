<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MovimientoController;
use App\Http\Controllers\SalidaController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\CotizacionController;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\DashboardController;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Aquí se registran las rutas para la aplicación. Todas estas rutas son 
| cargadas por el RouteServiceProvider.
*/

// Rutas Públicas (Login y Registro)
Route::get('/', function () {
    return view('login.login');
})->name('login');

Route::get('/crear-admin', function () {
    \App\Models\User::create([
        'name' => 'Admin',
        'email' => 'torresgiraldojhon1224@outlook.es',
        'password' => bcrypt('Jhonmiguel1224'),
        'razon_social' => 'Admin S.A.S',
        'nit' => '000000000',
        'telefono' => '0000000000',
        'estado' => 'activo',
        'role' => 'admin'
    ]);
});


Route::post('/login', [LoginController::class, 'login'])->name('login.store');

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Rutas Protegidas (Requieren autenticación)
Route::middleware(['auth'])->group(function () {

    // Dashboard / Página Principal (Ahora usa DashboardController)
    Route::get('/principal', [DashboardController::class, 'index'])->name('principal');

    // Módulos Principales (CRUDs)
    Route::resource('productos', ProductoController::class);
    Route::resource('proveedores', ProveedorController::class)->parameters(['proveedores' => 'proveedor']);
    Route::resource('movimientos', MovimientoController::class);
    
    // Cotizaciones con PDF
    Route::get('/cotizaciones/{cotizacion}/pdf', [CotizacionController::class, 'descargarPdf'])->name('cotizaciones.pdf');
    Route::resource('cotizaciones', CotizacionController::class);
    
    Route::resource('facturas', FacturaController::class);

    // Gestión de Facturas (Salidas)
    Route::prefix('salidas/{salida}')->group(function () {
        Route::get('/factura', [SalidaController::class, 'facturaPdf'])->name('salidas.factura');
        Route::get('/factura/download', [SalidaController::class, 'facturaDownload'])->name('salidas.factura.download');
        Route::post('/factura/enviar', [SalidaController::class, 'reenviarAFactus'])->name('salidas.factura.enviar');
        Route::delete('/', [SalidaController::class, 'destroy'])->name('salidas.destroy');
    });

    // Perfil de Usuario
    Route::get('/perfil', [PerfilController::class, 'index'])->name('perfil');
    Route::post('/perfil/password', [PerfilController::class, 'updatePassword'])->name('perfil.password');

    // Reportes e Historial
    Route::get('/historial-ventas', [MovimientoController::class, 'historial'])->name('historial.index');

    // Panel de Administración (Super Admin)
    Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/usuarios', [\App\Http\Controllers\Admin\AdminUserController::class, 'index'])->name('users.index');
        Route::post('/usuarios', [\App\Http\Controllers\Admin\AdminUserController::class, 'store'])->name('users.store');
        Route::patch('/usuarios/{user}/toggle', [\App\Http\Controllers\Admin\AdminUserController::class, 'toggleStatus'])->name('users.toggle');
        Route::delete('/usuarios/{user}', [\App\Http\Controllers\Admin\AdminUserController::class, 'destroy'])->name('users.destroy');
    });
});
