<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SalidaController;
use App\Http\Controllers\PerfilController;
use Illuminate\Http\Request;


Route::get('/', function () {
    return view('login.login');
})->name('login');
;


Route::post('/login', [LoginController::class, 'login'])
    ->name('login.store');
;

Route::get('/principal', function (Request $request) {
    if ($request->ajax()) {
        // Para loadView (SPA) devolvemos solo el contenido interno
        return view('principal._content');
    }

    // Para carga normal del navegador devolvemos la vista con layout
    return view('principal.principal');
})->name('principal')->middleware('auth');
;


Route::get('register', function () {
    return view('login.register');
})->name('register');

Route::post('register', [LoginController::class, 'register'])
    ->name('register.store');


Route::post('/logout', [LoginController::class, 'logout'])
    ->name('logout');


Route::resource('productos', App\Http\Controllers\ProductoController::class)
    ->middleware('auth');

Route::resource('proveedores', App\Http\Controllers\ProveedorController::class)
    ->parameters(['proveedores' => 'proveedor'])
    ->middleware('auth');


Route::resource('movimientos', App\Http\Controllers\MovimientoController::class)
    ->middleware('auth');

Route::resource('cotizacion', App\Http\Controllers\CotizacionController::class)
    ->middleware('auth');

Route::resource('facturas', App\Http\Controllers\FacturaController::class)->middleware('auth');


Route::get('/salidas/{salida}/factura', [SalidaController::class, 'facturaPdf'])
    ->name('salidas.factura')
    ->middleware('auth');

Route::middleware(['auth'])->group(function () {
    Route::get('/perfil', [PerfilController::class, 'index'])
        ->name('perfil');

});