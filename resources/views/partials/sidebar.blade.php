@php
    use App\Models\Modulo;
    use App\Models\Permiso;
    use App\Models\Pedido_para_obra;
    use App\Models\PresupuestoAprobado;
    $permisos = Permiso::where('area_id', session('usuario_area_id'))->get();
    $modulos = Modulo::all();
    $pedidosPendientes = Pedido_para_obra::where('estado', '1')->count();
    $presupuestoaprobados = PresupuestoAprobado::where('estado', '1')->count();
@endphp
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="#" class="brand-link">
        <span class="brand-text font-weight-light">Gavilan y Asociados</span>
    </a>
    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="info">
                <a href="#" class="d-block">Usuario: <b>{{ session('usuario_nombre') }} </b></a>
                <a href="#" class="d-block">Area: <b>{{ session('usuario_area') }} </b></a>
            </div>
        </div>
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="{{ url('/home') }}" class="nav-link active">
                        <i class="nav-icon fas fa-home"></i>
                        <p>
                            Menú Principal
                        </p>
                    </a>
                </li>
                @if ($permisos->where('modulo_id', Modulo::where('nombre', 'are_ing')->first()->id ?? null)->where('ver', 1)->isNotEmpty())
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-toolbox"></i>
                        <p>
                            Ingenieria
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    @if ($permisos->where('modulo_id', Modulo::where('nombre', 'pre_apr_ing')->first()->id ?? null)->where('ver', 1)->isNotEmpty())
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('presupuesto_aprobado.index') }}" class="nav-link">
                                <i class="nav-icon fas fa-minus"></i>
                                <p>Presupuesto Aprobado</p>
                            </a>
                        </li>
                    </ul>
                    @endif
                    @if ($permisos->where('modulo_id', Modulo::where('nombre', 'val_pre_apr')->first()->id ?? null)->where('ver', 1)->isNotEmpty())
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('validar_presupuesto.index') }}" class="nav-link">
                                <i class="nav-icon fas fa-minus"></i>
                                <p>Validar Presupuesto</p>
                            </a>
                        </li>
                    </ul>
                    @endif
                    @if ($permisos->where('modulo_id', Modulo::where('nombre', 'age_tra')->first()->id ?? null)->where('ver', 1)->isNotEmpty())
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('agendamiento.index') }}" class="nav-link">
                                <i class="nav-icon fas fa-minus"></i>
                                <p>Agendar Trabajos</p>
                            </a>
                        </li>
                    </ul>
                    @endif
                    @if ($permisos->where('modulo_id', Modulo::where('nombre', 'ges_tra')->first()->id ?? null)->where('ver', 1)->isNotEmpty())
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('gestiontrabajo.index') }}" class="nav-link">
                                <i class="nav-icon fas fa-minus"></i>
                                <p>Gestion de trabajos</p>
                            </a>
                        </li>
                    </ul>
                    @endif
                    @if ($permisos->where('modulo_id', Modulo::where('nombre', 'ped_obr_ing')->first()->id ?? null)->where('ver', 1)->isNotEmpty())
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('pedidobra.index') }}" class="nav-link">
                                <i class="nav-icon fas fa-minus"></i>
                                <p>Pedido para obra</p>
                            </a>
                        </li>
                    </ul>
                    @endif
                    @if ($permisos->where('modulo_id', Modulo::where('nombre', 'obr')->first()->id ?? null)->where('ver', 1)->isNotEmpty())
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('obras.index') }}" class="nav-link">
                                <i class="nav-icon fas fa-minus"></i>
                                <p>Gestion de obras</p>
                            </a>
                        </li>
                    </ul>
                    @endif
                </li>
                @endif
                @if ($permisos->where('modulo_id', Modulo::where('nombre', 'are_dep')->first()->id ?? null)->where('ver', 1)->isNotEmpty())
                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-warehouse"></i>
                            <p>
                                Deposito
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        @if ($permisos->where('modulo_id', Modulo::where('nombre', 'ped_obr_dep')->first()->id ?? null)->where('ver', 1)->isNotEmpty())
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('preparobra.index') }}" class="nav-link">
                                    <i class="nav-icon fas fa-minus"></i>
                                    <p>Pedido para obra
                                        @if ($pedidosPendientes > 0)
                                            <span class="badge badge-warning">{{ $pedidosPendientes }}</span>
                                        @endif
                                    </p>
                                </a>
                            </li>
                        </ul>
                        @endif
                    </li>
                @endif
                @if ($permisos->where('modulo_id', Modulo::where('nombre', 'are_adm')->first()->id ?? null)->where('ver', 1)->isNotEmpty())
                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-credit-card"></i>
                            <p>
                                Administracion
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        @if ($permisos->where('modulo_id', Modulo::where('nombre', 'pre_apr_adm')->first()->id ?? null)->where('ver', 1)->isNotEmpty())
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('trabajo_cobrar.index') }}" class="nav-link">
                                        <i class="nav-icon fas fa-minus"></i>
                                        <p>Trabajos Aprobados</p>
                                    </a>
                                </li>
                            </ul>
                        @endif
                    </li>
                @endif
                @if ($permisos->where('modulo_id', Modulo::where('nombre', 'man')->first()->id ?? null)->where('ver', 1)->isNotEmpty())
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-wrench"></i>
                        <p>
                            Mantenimiento
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    @if ($permisos->where('modulo_id', Modulo::where('nombre', 'ins')->first()->id ?? null)->where('ver', 1)->isNotEmpty())
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('insumos.index') }}" class="nav-link">
                                <i class="nav-icon fas fa-minus"></i>
                                <p>Insumos</p>
                            </a>
                        </li>
                    </ul>
                    @endif
                </li>
                @endif
                @if ($permisos->where('modulo_id', Modulo::where('nombre', 'her')->first()->id ?? null)->where('ver', 1)->isNotEmpty())
                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-folder-open"></i>
                            <p>
                                Herramientas
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        @if ($permisos->where('modulo_id', Modulo::where('nombre', 'gen_doc')->first()->id ?? null)->where('ver', 1)->isNotEmpty())
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('documentos.index') }}" class="nav-link">
                                        <i class="nav-icon fas fa-minus"></i>
                                        <p>Generador de Documentos</p>
                                    </a>
                                </li>
                            </ul>
                        @endif
                    </li>
                @endif
                @if ($permisos->where('modulo_id', Modulo::where('nombre', 'usu')->first()->id ?? null)->where('ver', 1)->isNotEmpty())
                <li class="nav-item">
                        <a href="{{ route('usuarios.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-users"></i>
                            <p>Usuarios</p>
                        </a>
                </li>
                @endif
                @if ($permisos->where('modulo_id', Modulo::where('nombre', 'per')->first()->id ?? null)->where('ver', 1)->isNotEmpty())
                <li class="nav-item">
                        <a href="{{ route('permisos.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-id-card"></i>
                            <p>Permisos</p>
                        </a>
                </li>
                @endif

                <li class="nav-item">
                    <a href="{{ route('logout') }}" class="nav-link"
                       onclick="event.preventDefault(); if(confirm('¿Estás seguro de que deseas cerrar sesión?')) { document.getElementById('logout-form').submit(); }">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>
                            Cerrar Sesion
                        </p>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
        </nav>
    </div>
</aside>
<style>
    .badge-warning {
        background-color: orange;
        color: white;
        border-radius: 50%;
        padding: 5px 10px;
        font-size: 12px;
    }
</style>

