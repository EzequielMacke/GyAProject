<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DocumentosController;
use App\Http\Controllers\InsumosController;
use App\Http\Controllers\ObrasController;
use App\Http\Controllers\PedobraController;
use App\Http\Controllers\PermisosController;
use App\Http\Controllers\PreparobraController;
use App\Http\Controllers\PresupuestoController;
use App\Http\Controllers\UsuariosController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

Route::get('/cache', function () {
    // Limpiar configuración
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('config:cache');
    return 'Caché limpiada correctamente';
});

Route::get('/incriptar', function () {
    // Ejecutar el comando para encriptar contraseñas
    Artisan::call('encrypt:passwords');
    return 'Contraseñas encriptadas correctamente';
});

Route::get('/test-conexion', function () {
    try {
        DB::connection()->getPdo();
        return 'Conexión exitosa a la base de datos!';
    } catch (\Exception $e) {
        return 'No se pudo conectar a la base de datos. Error: ' . $e->getMessage();
    }
});

Route::get('/', [AuthController::class, 'showLoginForm'])->name('welcome');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/home', function () {
    return view('home');
})->name('home');

// Ruta para la vista de carga de insumos
Route::get('/insumos/cargar', [InsumosController::class, 'create'])->name('insumos.create');
Route::post('/insumos', [InsumosController::class, 'store'])->name('insumos.store');
Route::get('/insumos', [InsumosController::class, 'index'])->name('insumos.index');

// Ruta para la vista de carga de usuarios
Route::get('/usuarios/cargar', [UsuariosController::class, 'create'])->name('usuarios.create');
Route::post('/usuarios', [UsuariosController::class, 'store'])->name('usuarios.store');
Route::get('/usuarios', [UsuariosController::class, 'index'])->name('usuarios.index');

// Ruta para la vista de carga de obras
Route::get('/obras/cargar', [ObrasController::class, 'create'])->name('obras.create');
Route::post('/obras', [ObrasController::class, 'store'])->name('obras.store');
Route::get('/obras', [ObrasController::class, 'index'])->name('obras.index');
Route::get('/obras/{id}', [ObrasController::class, 'show'])->name('obras.show');
Route::get('/obras/{id}/edit', [ObrasController::class, 'edit'])->name('obras.edit');
Route::put('/obras/{id}', [ObrasController::class, 'update'])->name('obras.update');

// Ruta para la vista de carga de pedidos de obras
Route::get('/pedidobra/cargar', [PedobraController::class, 'create'])->name('pedidobra.create');
Route::post('/pedidobra', [PedobraController::class, 'store'])->name('pedidobra.store');
Route::get('/pedidobra', [PedobraController::class, 'index'])->name('pedidobra.index');
Route::resource('pedidobra', PedobraController::class);
Route::get('/pedidobra/{id}/show', [PedobraController::class, 'show'])->name('pedidobra.show');
Route::get('/recargar_insumos', [PedobraController::class, 'getInsumos'])->name('insumos.recargar');
Route::get('/recargar_obras', [PedobraController::class, 'getObras'])->name('obras.recargar');
Route::get('/pedidobra/duplicar/{id}', [PedobraController::class, 'duplicar'])->name('pedidobra.duplicar');


// Ruta para la vista de preparacion de pedidos de obras
Route::get('/preparobra', [PreparobraController::class, 'index'])->name('preparobra.index');
Route::get('/preparobra/{id}/show', [PreparobraController::class, 'show'])->name('preparobra.show');
Route::put('/preparobra/{id}/updateConfirmado', [PreparobraController::class, 'updateConfirmado'])->name('preparobra.updateConfirmado');

// Ruta para la vista de permisos
Route::get('/permisos', [PermisosController::class, 'index'])->name('permisos.index');
Route::get('/permisos/{id}/editar', [PermisosController::class, 'edit'])->name('permisos.edit');
Route::put('/permisos/{id}', [PermisosController::class, 'update'])->name('permisos.update');





// Rutas para gestion de informes
Route::get('/documentos', [DocumentosController::class, 'index'])->name('documentos.index');
Route::get('/documentos/cargar', [DocumentosController::class, 'create'])->name('documentos.create');
Route::get('/ensayos-por-tipo/{tipoTrabajoId}', [DocumentosController::class, 'ensayosPorTipo'])->name('ensayos.por_tipo');
Route::post('/documentos', [DocumentosController::class, 'store'])->name('documentos.store');
Route::get('/documentos/{id}/edit', [DocumentosController::class, 'edit'])->name('documentos.edit');
Route::put('/documentos/{id}', [DocumentosController::class, 'update'])->name('documentos.update');
Route::get('/documentos/{id}/detalles', [DocumentosController::class, 'detalles'])->name('documentos.detalles');
Route::post('/documentos/{id}/detalles', [DocumentosController::class, 'guardarDetalles'])->name('documentos.detalles.guardar');
Route::get('documentos/{id}/generar-word', [DocumentosController::class, 'generarWord'])->name('documentos.generarWord');
Route::get('/documentos/{id}/reemplazar-marcadores', [DocumentosController::class, 'reemplazarMarcadoresInforme'])->name('documentos.reemplazarMarcadores');

// Rutas para gestion de presupuestos
Route::get('/presupuestos', [PresupuestoController::class, 'index'])->name('presupuestos.index');
Route::get('/presupuestos/create', [PresupuestoController::class, 'create'])->name('presupuestos.create');
Route::get('/presupuestos/reportes', [PresupuestoController::class, 'reportes'])->name('presupuestos.reportes');
Route::get('/presupuestos/reporte/{tipo}', [PresupuestoController::class, 'generarReporte'])->name('presupuestos.generar.reporte');
Route::post('/presupuestos', [PresupuestoController::class, 'store'])->name('presupuestos.store');
Route::get('/presupuestos/{id}/edit', [PresupuestoController::class, 'edit'])->name('presupuestos.edit');
Route::put('/presupuestos/{id}', [PresupuestoController::class, 'update'])->name('presupuestos.update');
Route::get('/presupuestos/{id}/download/{type}', [PresupuestoController::class, 'downloadFile'])->name('presupuestos.download-file');
Route::get('/presupuestos/{id}', [PresupuestoController::class, 'show'])->name('presupuestos.show');
Route::delete('/presupuestos/{id}', [PresupuestoController::class, 'destroy'])->name('presupuestos.destroy');


// Rutas para gestion de facturas
Route::get('/presupuestos/{id}/facturas/create', [PresupuestoController::class, 'createFactura'])->name('presupuestos.facturas.create');
Route::post('/presupuestos/{id}/facturas', [PresupuestoController::class, 'storeFactura'])->name('presupuestos.facturas.store');
Route::get('/facturas/{id}/download-adjunto', [PresupuestoController::class, 'downloadAdjuntoFactura'])->name('facturas.download-adjunto');
Route::get('/facturas/{id}/edit', [PresupuestoController::class, 'editFactura'])->name('facturas.edit');
Route::put('/facturas/{id}', [PresupuestoController::class, 'updateFactura'])->name('facturas.update');
Route::delete('/facturas/{id}', [PresupuestoController::class, 'destroyFactura'])->name('facturas.destroy');


// Rutas para gestion de recibos
Route::get('/facturas/{id}/recibos/create', [PresupuestoController::class, 'createRecibo'])->name('facturas.recibos.create');
Route::post('/facturas/{id}/recibos', [PresupuestoController::class, 'storeRecibo'])->name('facturas.recibos.store');
Route::get('/recibos/{id}/edit', [PresupuestoController::class, 'editRecibo'])->name('recibos.edit');
Route::put('/recibos/{id}', [PresupuestoController::class, 'updateRecibo'])->name('recibos.update');
Route::delete('/recibos/{id}', [PresupuestoController::class, 'destroyRecibo'])->name('recibos.destroy');
