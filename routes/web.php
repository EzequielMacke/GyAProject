<?php

use App\Http\Controllers\AgendamientoController;
use App\Http\Controllers\AsignarOrdenController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactoController;
use App\Http\Controllers\ControlGastoController;
use App\Http\Controllers\DirectorioController;
use App\Http\Controllers\DocumentosController;
use App\Http\Controllers\FacturaVentaController;
use App\Http\Controllers\GestiontrabajoController;
use App\Http\Controllers\InsumosController;
use App\Http\Controllers\KitController;
use App\Http\Controllers\MantenimientoController;
use App\Http\Controllers\ObraInfoController;
use App\Http\Controllers\ObrasController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\PedobraController;
use App\Http\Controllers\PermisosController;
use App\Http\Controllers\PreparobraController;
use App\Http\Controllers\PresupuestoaprobadoController;
use App\Http\Controllers\ReciboController;
use App\Http\Controllers\TabletController;
use App\Http\Controllers\TrabajosaprobadosController;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\ProblemaController;
use App\Http\Controllers\SituacionAvanceController;
use App\Http\Controllers\ValidarpresupuestoController;
use App\Http\Controllers\BibliografiaController;
use App\Http\Controllers\PlantillaController;
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

Route::get('/admin-a-obras', function () {
    Artisan::call('admin:add-to-obras');
    return 'Admin agregado a todas las obras correctamente';
});

Route::get('/agregar-obra/{usuario_id}', function ($usuario_id) {
    $obras = \App\Models\Obra::whereDoesntHave('directorios', function ($query) use ($usuario_id) {
        $query->where('usuario_id', $usuario_id);
    })->get();

    if ($obras->isEmpty()) {
        return "El usuario {$usuario_id} ya está en todas las obras.";
    }

    foreach ($obras as $obra) {
        \App\Models\Directorio::create([
            'obra_id'    => $obra->id,
            'usuario_id' => $usuario_id,
            'fecha'      => now(),
        ]);
    }

    return "Usuario {$usuario_id} agregado a {$obras->count()} obras correctamente.";
});

Route::get('/test-conexion', function () {
    try {
        DB::connection()->getPdo();
        return 'Conexión exitosa a la base de datos!';
    }
    catch (\Exception $e) {
        return 'No se pudo conectar a la base de datos. Error: ' . $e->getMessage();
    }
});

Route::get('/', [AuthController::class , 'showLoginForm'])->name('welcome');
Route::post('/login', [AuthController::class , 'login'])->name('login');
Route::post('/logout', [AuthController::class , 'logout'])->name('logout');

Route::get('/home', function () {
    if (!session('usuario_area_id')) {
        return redirect()->route('welcome');
    }
    return view('home');
})->name('home');

// ── Insumos ──────────────────────────────────────────────────────────────────
Route::middleware('permiso:ins,ver')->group(function () {
    Route::get('/insumos', [InsumosController::class, 'index'])->name('insumos.index');
});
Route::middleware('permiso:ins,agregar')->group(function () {
    Route::get('/insumos/cargar', [InsumosController::class, 'create'])->name('insumos.create');
    Route::post('/insumos', [InsumosController::class, 'store'])->name('insumos.store');
});

// ── Usuarios ─────────────────────────────────────────────────────────────────
Route::middleware('permiso:usu,ver')->group(function () {
    Route::get('/usuarios', [UsuariosController::class, 'index'])->name('usuarios.index');
});
Route::middleware('permiso:usu,agregar')->group(function () {
    Route::get('/usuarios/cargar', [UsuariosController::class, 'create'])->name('usuarios.create');
    Route::post('/usuarios', [UsuariosController::class, 'store'])->name('usuarios.store');
});
Route::middleware('permiso:usu,editar')->group(function () {
    Route::get('/usuarios/{id}/editar', [UsuariosController::class, 'edit'])->name('usuarios.edit');
    Route::put('/usuarios/{id}', [UsuariosController::class, 'update'])->name('usuarios.update');
});

// ── Obras ─────────────────────────────────────────────────────────────────────
Route::middleware('permiso:obr,ver')->group(function () {
    Route::get('/obras/index', [ObrasController::class, 'index'])->name('obras.index');
    Route::get('/obras/{id}/show', [ObrasController::class, 'show'])->name('obras.show');
});
Route::middleware('permiso:obr,agregar')->group(function () {
    Route::get('/obras/cargar', [ObrasController::class, 'create'])->name('obras.create');
    Route::post('/obras/guardar', [ObrasController::class, 'store'])->name('obras.store');
});
Route::middleware('permiso:obr,editar')->group(function () {
    Route::get('/obras/{id}/edit', [ObrasController::class, 'edit'])->name('obras.edit');
    Route::put('/obras/{id}/update', [ObrasController::class, 'update'])->name('obras.update');
});
Route::middleware('permiso:obr,eliminar')->group(function () {
    Route::delete('/obras/{id}/destroy', [ObrasController::class, 'destroy'])->name('obras.destroy');
});

// ── Pedidos de obra ───────────────────────────────────────────────────────────
Route::middleware('permiso:ped_ins,ver')->group(function () {
    Route::get('/pedidobra/index/{obra?}', [PedobraController::class, 'index'])->name('pedidobra.index');
    Route::get('/pedidobra/{id}/show', [PedobraController::class, 'show'])->name('pedidobra.show');
    Route::get('/recargar_insumos', [PedobraController::class, 'getInsumos'])->name('insumos.recargar');
    Route::get('/recargar_obras', [PedobraController::class, 'getObras'])->name('obras.recargar');
});
Route::middleware('permiso:ped_ins,agregar')->group(function () {
    Route::get('/pedidobra/cargar/{obra?}', [PedobraController::class, 'create'])->name('pedidobra.create');
    Route::post('/pedidobra', [PedobraController::class, 'store'])->name('pedidobra.store');
    Route::get('/pedidobra/duplicar/{id}', [PedobraController::class, 'duplicar'])->name('pedidobra.duplicar');
});
Route::middleware('permiso:ped_ins,editar')->group(function () {
    Route::get('/pedidobra/{id}/edit', [PedobraController::class, 'edit'])->name('pedidobra.edit');
    Route::put('/pedidobra/{id}/update', [PedobraController::class, 'update'])->name('pedidobra.update');
});

// ── Preparar pedidos (Depósito) ───────────────────────────────────────────────
Route::middleware('permiso:pre_ped,ver')->group(function () {
    Route::get('/preparobra', [PreparobraController::class, 'index'])->name('preparobra.index');
    Route::get('/preparobra/{id}/show', [PreparobraController::class, 'show'])->name('preparobra.show');
});
Route::middleware('permiso:pre_ped,editar')->group(function () {
    Route::put('/preparobra/{id}/updateConfirmado', [PreparobraController::class, 'updateConfirmado'])->name('preparobra.updateConfirmado');
});

// ── Permisos ──────────────────────────────────────────────────────────────────
Route::middleware('permiso:per,ver')->group(function () {
    Route::get('/permisos', [PermisosController::class, 'index'])->name('permisos.index');
});
Route::middleware('permiso:per,editar')->group(function () {
    Route::get('/permisos/{id}/editar', [PermisosController::class, 'edit'])->name('permisos.edit');
    Route::put('/permisos/{id}', [PermisosController::class, 'update'])->name('permisos.update');
});

// ── Presupuestos aprobados ────────────────────────────────────────────────────
Route::middleware('permiso:pre_apr,ver')->group(function () {
    Route::get('/presupuesto_aprobado/{obra?}', [PresupuestoaprobadoController::class, 'index'])->name('presupuesto_aprobado.index');
});
Route::middleware('permiso:pre_apr,agregar')->group(function () {
    Route::get('/presupuesto_aprobado/cargar/{obra?}', [PresupuestoaprobadoController::class, 'create'])->name('presupuesto_aprobado.create');
    Route::post('/presupuesto_aprobado', [PresupuestoaprobadoController::class, 'store'])->name('presupuesto_aprobado.store');
});
Route::middleware('permiso:pre_apr,editar')->group(function () {
    Route::get('/presupuesto_aprobado/{id}/editar', [PresupuestoaprobadoController::class, 'edit'])->name('presupuesto_aprobado.edit');
    Route::put('/presupuesto_aprobado/{id}', [PresupuestoaprobadoController::class, 'update'])->name('presupuesto_aprobado.update');
});

// ── Control de gastos ─────────────────────────────────────────────────────────
Route::middleware('permiso:con_gas,ver')->group(function () {
    Route::get('/control_gastos/{obra}', [ControlGastoController::class, 'index'])->name('control_gastos.index');
    Route::get('/control_gastos/{obra}/presupuesto/{presupuesto}/nuevo', [ControlGastoController::class, 'create'])->name('control_gastos.create');
});
Route::middleware('permiso:con_gas,agregar')->group(function () {
    Route::post('/control_gastos', [ControlGastoController::class, 'store'])->name('control_gastos.store');
});
Route::middleware('permiso:con_gas,editar')->group(function () {
    Route::put('/control_gastos/{id}', [ControlGastoController::class, 'update'])->name('control_gastos.update');
});

// ── Validar presupuestos ──────────────────────────────────────────────────────
Route::middleware('permiso:pre_apr,editar')->group(function () {
    Route::get('/validar_presupuesto', [ValidarpresupuestoController::class, 'index'])->name('validar_presupuesto.index');
    Route::resource('validar_presupuesto', ValidarpresupuestoController::class);
    Route::post('validar_presupuesto/check', [ValidarpresupuestoController::class, 'checkObra'])->name('validar_presupuesto.check');
    Route::post('validar_presupuesto/anular/{id}', [ValidarpresupuestoController::class, 'anular'])->name('validar_presupuesto.anular');
});

// ── Trabajos a cobrar ─────────────────────────────────────────────────────────
Route::middleware('permiso:pre_apr,ver')->group(function () {
    Route::get('/trabajo_cobrar', [TrabajosaprobadosController::class, 'index'])->name('trabajo_cobrar.index');
});
Route::middleware('permiso:pre_apr,editar')->group(function () {
    Route::post('/trabajo_cobrar', [TrabajosaprobadosController::class, 'store'])->name('trabajo_cobrar.store');
    Route::post('/trabajo_cobrar/anular/{id}', [TrabajosaprobadosController::class, 'anular'])->name('trabajo_cobrar.anular');
});

// ── Agendamiento ──────────────────────────────────────────────────────────────
Route::middleware('permiso:pre_apr,ver')->group(function () {
    Route::get('/agendamiento', [AgendamientoController::class, 'index'])->name('agendamiento.index');
    Route::resource('agendamiento', AgendamientoController::class);
});

// ── Gestión de trabajos ───────────────────────────────────────────────────────
Route::middleware('permiso:pre_apr,ver')->group(function () {
    Route::get('/gestiontrabajo', [GestiontrabajoController::class, 'index'])->name('gestiontrabajo.index');
});

// ── Documentos / Herramientas ─────────────────────────────────────────────────
Route::middleware('permiso:her,ver')->group(function () {
    Route::get('/documentos', [DocumentosController::class, 'index'])->name('documentos.index');
    Route::get('/documentos/{id}/detalles', [DocumentosController::class, 'detalles'])->name('documentos.detalles');
    Route::get('documentos/{id}/generar-word', [DocumentosController::class, 'generarWord'])->name('documentos.generarWord');
    Route::get('/documentos/{id}/reemplazar-marcadores', [DocumentosController::class, 'reemplazarMarcadoresInforme'])->name('documentos.reemplazarMarcadores');
    Route::get('/ensayos-por-tipo/{tipoTrabajoId}', [DocumentosController::class, 'ensayosPorTipo'])->name('ensayos.por_tipo');
});
Route::middleware('permiso:her,agregar')->group(function () {
    Route::get('/documentos/cargar', [DocumentosController::class, 'create'])->name('documentos.create');
    Route::post('/documentos', [DocumentosController::class, 'store'])->name('documentos.store');
});
Route::middleware('permiso:her,editar')->group(function () {
    Route::get('/documentos/{id}/edit', [DocumentosController::class, 'edit'])->name('documentos.edit');
    Route::put('/documentos/{id}', [DocumentosController::class, 'update'])->name('documentos.update');
    Route::post('/documentos/{id}/detalles', [DocumentosController::class, 'guardarDetalles'])->name('documentos.detalles.guardar');
});

// ── Directorio ────────────────────────────────────────────────────────────────
Route::middleware('permiso:dir,ver')->group(function () {
    Route::get('/directorio/{obra}', [DirectorioController::class, 'index'])->name('directorio.index');
});
Route::middleware('permiso:dir,agregar')->group(function () {
    Route::get('/directorio/{obra}/create', [DirectorioController::class, 'create'])->name('directorio.create');
    Route::post('/directorio/{obra}', [DirectorioController::class, 'store'])->name('directorio.store');
});
Route::middleware('permiso:dir,eliminar')->group(function () {
    Route::delete('/directorio/{obra}/{directorio}', [DirectorioController::class, 'destroy'])->name('directorio.destroy');
});

// ── Contactos ─────────────────────────────────────────────────────────────────
Route::middleware('permiso:con,ver')->group(function () {
    Route::get('/contacto/{obra}', [ContactoController::class, 'index'])->name('contacto.index');
});
Route::middleware('permiso:con,agregar')->group(function () {
    Route::get('contacto/create/{obra?}', [ContactoController::class, 'create'])->name('contacto.create');
    Route::post('/contacto', [ContactoController::class, 'store'])->name('contacto.store');
});
Route::middleware('permiso:con,editar')->group(function () {
    Route::get('/contacto/{id}/edit', [ContactoController::class, 'edit'])->name('contacto.edit');
    Route::put('/contacto/{id}', [ContactoController::class, 'update'])->name('contacto.update');
});

// ── Facturas de venta ─────────────────────────────────────────────────────────
Route::middleware('permiso:fac,ver')->group(function () {
    Route::get('/factura_venta/buscar', [FacturaVentaController::class, 'search'])->name('factura_venta.search');
    Route::get('/factura_venta/obra/{obraId}/presupuestos', [FacturaVentaController::class, 'show'])->name('factura_venta.show');
});
Route::middleware('permiso:fac,agregar')->group(function () {
    Route::get('/factura_venta/cargar/{presupuesto?}/{obra?}', [FacturaVentaController::class, 'create'])->name('factura_venta.create');
    Route::post('/factura_venta', [FacturaVentaController::class, 'store'])->name('factura_venta.store');
});
Route::middleware('permiso:fac,editar')->group(function () {
    Route::get('/factura_venta/{id}/edit', [FacturaVentaController::class, 'edit'])->name('factura_venta.edit');
    Route::put('/factura_venta/{id}', [FacturaVentaController::class, 'update'])->name('factura_venta.update');
});
Route::middleware('permiso:fac,eliminar')->group(function () {
    Route::delete('/factura_venta/{id}', [FacturaVentaController::class, 'destroy'])->name('factura_venta.destroy');
});
Route::middleware('permiso:fac,ver')->group(function () {
    Route::get('/factura_venta/{presupuesto?}/{obra?}', [FacturaVentaController::class, 'index'])->name('factura_venta.index');
});

// ── Recibos de venta ──────────────────────────────────────────────────────────
Route::middleware('permiso:fac,agregar')->group(function () {
    Route::get('/recibo_venta/cargar/{presupuesto?}/{obra?}/{factura?}', [ReciboController::class, 'create'])->name('recibo_venta.create');
    Route::post('/recibo_venta/guardar', [ReciboController::class, 'store'])->name('recibo_venta.store');
});
Route::middleware('permiso:fac,editar')->group(function () {
    Route::get('/recibo_venta/{id}/edit', [ReciboController::class, 'edit'])->name('recibo_venta.edit');
    Route::put('/recibo_venta/{id}', [ReciboController::class, 'update'])->name('recibo_venta.update');
});
Route::middleware('permiso:fac,eliminar')->group(function () {
    Route::delete('/recibo_venta/{id}', [ReciboController::class, 'destroy'])->name('recibo_venta.destroy');
});
Route::middleware('permiso:fac,ver')->group(function () {
    Route::get('/recibo_venta/{presupuesto?}/{obra?}/{factura?}', [ReciboController::class, 'index'])->name('recibo_venta.index');
});

// ── Tabletas ──────────────────────────────────────────────────────────────────
Route::middleware('permiso:tab,ver')->group(function () {
    Route::get('/tabletas/index', [TabletController::class, 'index'])->name('tabletas.index');
    });
    Route::get('/tabletas/assign/{clave}', [TabletController::class, 'assignShow'])->name('tabletas.assign.show');
    Route::get('/tabletas/thanks', [TabletController::class, 'thanks'])->name('tabletas.thanks');
    Route::post('/tabletas/assign/{clave}', [TabletController::class, 'assignRetiro'])->name('tabletas.assign.retiro');
    Route::post('/tabletas/devolucion/{clave}', [TabletController::class, 'devolucion'])->name('tabletas.devolucion');
    Route::get('/tabletas/info/{clave}', [TabletController::class, 'info'])->name('tabletas.info');

Route::middleware('permiso:tab,eliminar')->group(function () {
    Route::get('/tabletas/generar-qrs', [TabletController::class, 'generarQrs'])->name('tabletas.generarQrs');
    Route::get('/tabletas/report', [TabletController::class, 'report'])->name('tabletas.report');
});
Route::middleware('permiso:tab,agregar')->group(function () {
    Route::get('/tabletas/create', [TabletController::class, 'create'])->name('tabletas.create');
    Route::post('/tabletas/store', [TabletController::class, 'store'])->name('tabletas.store');
});
Route::middleware('permiso:tab,editar')->group(function () {
    Route::get('/tabletas/{id}/edit', [TabletController::class, 'edit'])->name('tabletas.edit');
    Route::put('/tabletas/{id}', [TabletController::class, 'update'])->name('tabletas.update');
});
Route::middleware('permiso:ret_tab,agregar')->group(function () {
    Route::get('/tabletas/retiro', [TabletController::class, 'retiro'])->name('tabletas.retiro');
    Route::post('/tabletas/retiro', [TabletController::class, 'retiroStore'])->name('tabletas.retiro.store');
    Route::get('/tabletas/devolucion', [TabletController::class, 'devolucionIndex'])->name('tabletas.devolucion.index');
    Route::post('/tabletas/devolucion/registrar/{id}', [TabletController::class, 'devolucionRegistrar'])->name('tabletas.devolucion.registrar');
});
Route::middleware('permiso:ret_tab,eliminar')->group(function () {
    Route::get('/tabletas/aprobacion', [TabletController::class, 'aprobacion'])->name('tabletas.aprobacion');
    Route::put('/tabletas/aprobacion/{id}/aprobar', [TabletController::class, 'aprobar'])->name('tabletas.aprobacion.aprobar');
    Route::delete('/tabletas/aprobacion/{id}', [TabletController::class, 'denegar'])->name('tabletas.aprobacion.denegar');
    Route::put('/tabletas/aprobacion/{id}/aprobar-devolucion', [TabletController::class, 'aprobarDevolucion'])->name('tabletas.aprobacion.aprobarDevolucion');
    Route::put('/tabletas/aprobacion/{id}/denegar-devolucion', [TabletController::class, 'denegarDevolucion'])->name('tabletas.aprobacion.denegarDevolucion');
});

// ── Mantenimiento ─────────────────────────────────────────────────────────────
Route::middleware('permiso:man,ver')->group(function () {
    Route::get('/mantenimiento/show', [MantenimientoController::class, 'show'])->name('mantenimiento.show');
});

// ── Kits ──────────────────────────────────────────────────────────────────────
Route::middleware('permiso:kit,ver')->group(function () {
    Route::get('/kits/index', [KitController::class, 'index'])->name('kits.index');
});
Route::middleware('permiso:kit,agregar')->group(function () {
    Route::get('/kits/create', [KitController::class, 'create'])->name('kits.create');
    Route::post('/kits/store', [KitController::class, 'store'])->name('kits.store');
});
Route::middleware('permiso:kit,editar')->group(function () {
    Route::get('/kits/{id}/edit', [KitController::class, 'edit'])->name('kits.edit');
    Route::put('/kits/{id}', [KitController::class, 'update'])->name('kits.update');
});

// ── Asignar orden de trabajo ──────────────────────────────────────────────────
Route::middleware('permiso:asi_ord,ver')->group(function () {
    Route::get('/asignar-orden', [AsignarOrdenController::class, 'index'])->name('asignar_orden.index');
});
Route::middleware('permiso:asi_ord,editar')->group(function () {
    Route::get('/asignar-orden/{id}/edit', [AsignarOrdenController::class, 'edit'])->name('asignar_orden.edit');
    Route::put('/asignar-orden/{id}', [AsignarOrdenController::class, 'update'])->name('asignar_orden.update');
});

// ── Datos / Resumen de obra ───────────────────────────────────────────────────
Route::middleware('permiso:dat,ver')->group(function () {
    Route::get('/obras/{id}/informacion', [ObraInfoController::class, 'show'])->name('obra_info.show');
});

// ── Situación de avance ───────────────────────────────────────────────────────
Route::middleware('permiso:sit_ava,ver')->group(function () {
    Route::get('/situacion_avance', [SituacionAvanceController::class, 'index'])->name('situacion_avance.index');
    Route::get('/situacion_avance/reporte', [SituacionAvanceController::class, 'report'])->name('situacion_avance.report');
    Route::get('/situacion_avance/reporte/pdf', [SituacionAvanceController::class, 'reportePdf'])->name('situacion_avance.report.pdf');
});
Route::middleware('permiso:sit_ava,agregar')->group(function () {
    Route::put('/situacion_avance/{id}', [SituacionAvanceController::class, 'update'])->name('situacion_avance.update');
});

// ── Problemas ─────────────────────────────────────────────────────────────────
Route::middleware('permiso:pro,ver')->group(function () {
    Route::get('/problemas', [ProblemaController::class, 'index'])->name('problemas.index');
    Route::get('/problemas/{id}/detalle', [ProblemaController::class, 'detalle'])->name('problemas.detalle');
    Route::get('/problemas/{id}/show', [ProblemaController::class, 'show'])->name('problemas.show');
    Route::get('/soluciones/{id}/detalle', [ProblemaController::class, 'detalleSolucion'])->name('soluciones.detalle');
});
Route::middleware('permiso:pro,agregar')->group(function () {
    Route::post('/problemas', [ProblemaController::class, 'store'])->name('problemas.store');
    Route::post('/problemas/{id}/fotos', [ProblemaController::class, 'storeFoto'])->name('problemas.fotos.store');
});
Route::middleware('permiso:sol,agregar')->group(function () {
    Route::post('/problemas/{problema_id}/soluciones', [ProblemaController::class, 'storeSolucion'])->name('problemas.soluciones.store');
    Route::post('/soluciones/{id}/fotos', [ProblemaController::class, 'storeFotoSolucion'])->name('soluciones.fotos.store');
});
Route::middleware('permiso:pro,editar')->group(function () {
    Route::post('/problemas/reordenar', [ProblemaController::class, 'reordenarProblemas'])->name('problemas.reordenar');
    Route::put('/problemas/{id}', [ProblemaController::class, 'update'])->name('problemas.update');
    Route::put('/problemas/{id}/observacion', [ProblemaController::class, 'updateObservacion'])->name('problemas.observacion');
});
Route::middleware('permiso:pro,eliminar')->group(function () {
    Route::delete('/problemas/{id}', [ProblemaController::class, 'destroy'])->name('problemas.destroy');
    Route::delete('/problema-detalles/{id}', [ProblemaController::class, 'destroyFoto'])->name('problemas.fotos.destroy');
});
Route::middleware('permiso:sol,editar')->group(function () {
    Route::post('/soluciones/reordenar', [ProblemaController::class, 'reordenarSoluciones'])->name('soluciones.reordenar');
    Route::put('/soluciones/{id}', [ProblemaController::class, 'updateSolucion'])->name('soluciones.update');
    Route::put('/soluciones/{id}/observacion', [ProblemaController::class, 'updateObservacionSolucion'])->name('soluciones.observacion');
});
Route::middleware('permiso:sol,eliminar')->group(function () {
    Route::delete('/soluciones/{id}', [ProblemaController::class, 'destroySolucion'])->name('soluciones.destroy');
    Route::put('/soluciones/{id}/restaurar', [ProblemaController::class, 'restaurarSolucion'])->name('soluciones.restaurar');
    Route::delete('/solucion-detalles/{id}', [ProblemaController::class, 'destroyFotoSolucion'])->name('soluciones.fotos.destroy');
});

// ── Buscador global (sin restricción de módulo) ───────────────────────────────
Route::middleware('permiso:obr,ver')->get('/buscar', [SearchController::class, 'search'])->name('search.global');

// ── Bibliografía ──────────────────────────────────────────────────────────────
// Rutas estáticas primero para evitar conflicto con /{id}
Route::middleware('permiso:bib,ver')->group(function () {
    Route::get('/bibliografia', [BibliografiaController::class, 'index'])->name('bibliografia.index');
    Route::get('/bibliografia/generar', [BibliografiaController::class, 'generate'])->name('bibliografia.generate');
    Route::post('/bibliografia/generar', [BibliografiaController::class, 'generateWord'])->name('bibliografia.generateWord');
});
Route::middleware('permiso:bib,agregar')->group(function () {
    Route::get('/bibliografia/crear', [BibliografiaController::class, 'create'])->name('bibliografia.create');
    Route::post('/bibliografia', [BibliografiaController::class, 'store'])->name('bibliografia.store');
});
// Rutas dinámicas /{id} al final
Route::middleware('permiso:bib,ver')->group(function () {
    Route::get('/bibliografia/{id}', [BibliografiaController::class, 'show'])->name('bibliografia.show');
});
Route::middleware('permiso:bib,editar')->group(function () {
    Route::get('/bibliografia/{id}/editar', [BibliografiaController::class, 'edit'])->name('bibliografia.edit');
    Route::put('/bibliografia/{id}', [BibliografiaController::class, 'update'])->name('bibliografia.update');
});
Route::middleware('permiso:bib,eliminar')->group(function () {
    Route::delete('/bibliografia/{id}', [BibliografiaController::class, 'destroy'])->name('bibliografia.destroy');
});


// ── Plantilla ─────────────────────────────────────────────────────────────────
Route::middleware('permiso:pla,ver')->group(function () {
    Route::get('/plantilla', [PlantillaController::class, 'index'])->name('plantilla.index');
    Route::get('/plantilla/{id}/descargar', [PlantillaController::class, 'download'])->name('plantilla.download');
});
Route::middleware('permiso:pla,agregar')->group(function () {
    Route::get('/plantilla/crear', [PlantillaController::class, 'create'])->name('plantilla.create');
    Route::post('/plantilla', [PlantillaController::class, 'store'])->name('plantilla.store');
});
Route::middleware('permiso:pla,ver')->group(function () {
    Route::get('/plantilla/{id}', [PlantillaController::class, 'show'])->name('plantilla.show');
});
Route::middleware('permiso:pla,editar')->group(function () {
    Route::get('/plantilla/{id}/editar', [PlantillaController::class, 'edit'])->name('plantilla.edit');
    Route::put('/plantilla/{id}', [PlantillaController::class, 'update'])->name('plantilla.update');
    Route::post('/plantilla/{id}/revision', [PlantillaController::class, 'storeRevision'])->name('plantilla.revision');
});
Route::middleware('permiso:pla,eliminar')->group(function () {
    Route::delete('/plantilla/{id}', [PlantillaController::class, 'destroy'])->name('plantilla.destroy');
});

//ruta de pizarra
Route::get('/pizarra', function () {
    return view('pizarra.pizarra');
})->name('pizarra');

//ruta de pizarrav2
Route::get('/pizarrav2', function () {
    return view('pizarra.pizarrav2');
})->name('pizarra.v2');