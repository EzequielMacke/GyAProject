<?php

use App\Http\Controllers\AgendamientoController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactoController;
use App\Http\Controllers\DirectorioController;
use App\Http\Controllers\DocumentosController;
use App\Http\Controllers\FacturaVentaController;
use App\Http\Controllers\GestiontrabajoController;
use App\Http\Controllers\InsumosController;
use App\Http\Controllers\ObrasController;
use App\Http\Controllers\PedobraController;
use App\Http\Controllers\PermisosController;
use App\Http\Controllers\PreparobraController;
use App\Http\Controllers\PresupuestoaprobadoController;
use App\Http\Controllers\ReciboController;
use App\Http\Controllers\TabletController;
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
Route::post('/obras/guardar', [ObrasController::class, 'store'])->name('obras.store');
Route::get('/obras/index', [ObrasController::class, 'index'])->name('obras.index');
Route::get('/obras/{id}', [ObrasController::class, 'show'])->name('obras.show');

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
Route::get('/presupuesto_aprobado/{obra?}', [PresupuestoaprobadoController::class, 'index'])->name('presupuesto_aprobado.index');
Route::get('/presupuesto_aprobado/cargar/{obra?}', [PresupuestoaprobadoController::class, 'create'])->name('presupuesto_aprobado.create');
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

//Rutas de directorio
Route::get('/directorio/{obra}', [DirectorioController::class, 'index'])->name('directorio.index');
Route::get('/directorio/{obra}/create', [DirectorioController::class, 'create'])->name('directorio.create');
Route::post('/directorio/{obra}', [DirectorioController::class, 'store'])->name('directorio.store');

//Ruta para contactos
Route::get('/contacto/{obra}', [ContactoController::class, 'index'])->name('contacto.index');
Route::get('contacto/create/{obra?}', [ContactoController::class, 'create'])->name('contacto.create');
Route::post('/contacto', [ContactoController::class, 'store'])->name('contacto.store');
Route::get('/contacto/{id}/edit', [ContactoController::class, 'edit'])->name('contacto.edit');
Route::put('/contacto/{id}', [ContactoController::class, 'update'])->name('contacto.update');

// Rutas para facturas de venta
Route::get('/factura_venta/cargar/{presupuesto?}/{obra?}', [FacturaVentaController::class, 'create'])->name('factura_venta.create');
Route::post('/factura_venta', [FacturaVentaController::class, 'store'])->name('factura_venta.store');
Route::get('/factura_venta/obra/{obraId}/presupuestos', [FacturaVentaController::class, 'show'])->name('factura_venta.show');
Route::get('/factura_venta/{id}/edit', [FacturaVentaController::class, 'edit'])->name('factura_venta.edit');
Route::put('/factura_venta/{id}', [FacturaVentaController::class, 'update'])->name('factura_venta.update');
Route::get('/factura_venta/{presupuesto?}/{obra?}', [FacturaVentaController::class, 'index'])->name('factura_venta.index');

// Rutas para tablets
Route::get('/tabletas/index', [TabletController::class, 'index'])->name('tabletas.index');
Route::get('/tabletas/create', [TabletController::class, 'create'])->name('tabletas.create');
Route::post('/tabletas/store', [TabletController::class, 'store'])->name('tabletas.store');
Route::get('/tabletas/generar-qrs', [TabletController::class, 'generarQrs'])->name('tabletas.generarQrs');
Route::get('/tabletas/assign/{clave}', [TabletController::class, 'assignShow'])->name('tabletas.assign.show');
Route::post('/tabletas/assign/{clave}', [TabletController::class, 'assignRetiro'])->name('tabletas.assign.retiro');
Route::post('/tabletas/devolucion/{clave}', [TabletController::class, 'devolucion'])->name('tabletas.devolucion');
Route::get('/tabletas/thanks', [TabletController::class, 'thanks'])->name('tabletas.thanks');
Route::get('/tabletas/report', [TabletController::class, 'report'])->name('tabletas.report');

// Rutas para recibos de venta
Route::get('/recibo_venta/{id}/edit', [ReciboController::class, 'edit'])->name('recibo_venta.edit');
Route::put('/recibo_venta/{id}', [ReciboController::class, 'update'])->name('recibo_venta.update');
Route::get('/recibo_venta/cargar/{presupuesto?}/{obra?}/{factura?}', [ReciboController::class, 'create'])->name('recibo_venta.create');
Route::post('/recibo_venta/guardar', [ReciboController::class, 'store'])->name('recibo_venta.store');
Route::get('/recibo_venta/{presupuesto?}/{obra?}/{factura?}', [ReciboController::class, 'index'])->name('recibo_venta.index');

