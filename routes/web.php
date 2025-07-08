<?php

use App\Http\Controllers\AgendamientoController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DocumentosController;
use App\Http\Controllers\GestiontrabajoController;
use App\Http\Controllers\InsumosController;
use App\Http\Controllers\ObrasController;
use App\Http\Controllers\PedobraController;
use App\Http\Controllers\PermisosController;
use App\Http\Controllers\PreparobraController;
use App\Http\Controllers\PresupuestoaprobadoController;
use App\Http\Controllers\TrabajosaprobadosController;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\ValidarpresupuestoController;
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
Route::resource('obras', ObrasController::class);

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

// Rutas para la vista de presupuestos aprobados
Route::get('/presupuesto_aprobado', [PresupuestoaprobadoController::class, 'index'])->name('presupuesto_aprobado.index');
Route::get('/presupuesto_aprobado/cargar', [PresupuestoAprobadoController::class, 'create'])->name('presupuesto_aprobado.create');
Route::post('/presupuesto_aprobado', [PresupuestoAprobadoController::class, 'store'])->name('presupuesto_aprobado.store');
Route::get('/presupuesto_aprobado/{id}/editar', [PresupuestoAprobadoController::class, 'edit'])->name('presupuesto_aprobado.edit');
Route::put('/presupuesto_aprobado/{id}', [PresupuestoAprobadoController::class, 'update'])->name('presupuesto_aprobado.update');


// Rutas para la validar presupuestos aprobados
Route::get('/validar_presupuesto', [ValidarpresupuestoController::class, 'index'])->name('validar_presupuesto.index');
Route::get('/validar_presupuesto', [ValidarpresupuestoController::class, 'store'])->name('validar_presupuesto.store');
Route::resource('validar_presupuesto', ValidarpresupuestoController::class);
Route::post('validar_presupuesto/check', [ValidarpresupuestoController::class, 'checkObra'])->name('validar_presupuesto.check');
Route::post('validar_presupuesto/anular/{id}', [ValidarpresupuestoController::class, 'anular'])->name('validar_presupuesto.anular');

// Rutas para la cobrar presupuestos aprobados
Route::get('/trabajo_cobrar', [TrabajosaprobadosController::class, 'index'])->name('trabajo_cobrar.index');
Route::post('/trabajo_cobrar', [TrabajosaprobadosController::class, 'store'])->name('trabajo_cobrar.store');
Route::post('/trabajo_cobrar/anular/{id}', [TrabajosaprobadosController::class, 'anular'])->name('trabajo_cobrar.anular');

// Rutas para agendamiento
Route::get('/agendamiento', [AgendamientoController::class, 'index'])->name('agendamiento.index');
Route::resource('agendamiento', AgendamientoController::class);

// Rutas para gestion de trabajos
Route::get('/gestiontrabajo', [GestiontrabajoController::class, 'index'])->name('gestiontrabajo.index');


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
