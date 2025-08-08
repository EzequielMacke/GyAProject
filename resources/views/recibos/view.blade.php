<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Presupuesto</title>
    @include('partials.head')
    @php
        use App\Models\Modulo;
        use App\Models\Permiso;
        $permisos = Permiso::where('area_id', session('usuario_area_id'))->get();
    @endphp
    @if ($permisos->where('modulo_id', Modulo::where('nombre', 'pre')->first()->id ?? null)->where('ver', 1)->isEmpty())
        <script>
            window.location.href = "{{ url('/home') }}";
        </script>
    @endif
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        @include('partials.navbar')
        @include('partials.sidebar')
        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Detalles del Presupuesto</h1>
                        </div>
                        <div class="col-sm-6">
                            <a href="{{ route('presupuestos.index') }}" class="btn btn-secondary float-right">Volver al Listado</a>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ $presupuesto->nombre }}</h3>
                            <div class="card-tools">
                                @if ($permisos->where('modulo_id', Modulo::where('nombre', 'pre')->first()->id ?? null)->where('editar', 1)->isNotEmpty())
                                    <a href="{{ route('presupuestos.edit', $presupuesto->id) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                @endif
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <!-- Información General -->
                                <div class="col-md-6">
                                    <h5>Información General</h5>
                                    <table class="table table-bordered">
                                        <tr>
                                            <th style="width: 40%;">Nombre:</th>
                                            <td>{{ $presupuesto->nombre }}</td>
                                        </tr>
                                        <tr>
                                            <th>Obra:</th>
                                            <td>{{ $presupuesto->obra->nombre ?? 'Sin obra' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Orden de Trabajo:</th>
                                            <td>
                                                @if($presupuesto->orden_trabajo)
                                                    <span class="badge badge-info">{{ $presupuesto->orden_trabajo }}</span>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Tipo de Trabajo:</th>
                                            <td>{{ $presupuesto->tipoTrabajo->nombre ?? 'Sin tipo' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Estado:</th>
                                            <td>
                                                <span class="px-2 py-1 rounded" style="
                                                    background-color:
                                                        @switch($presupuesto->estado_id)
                                                            @case(1) #28a745; @break
                                                            @case(2) #ffc107; @break
                                                            @case(3) #007bff; @break
                                                            @case(4) #dc3545; @break
                                                            @default #6c757d;
                                                        @endswitch
                                                    color: #fff;
                                                ">
                                                    {{ $presupuesto->estado->descripcion ?? 'Sin estado' }}
                                                </span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>

                                <!-- Información Financiera -->
                                <div class="col-md-6">
                                    <h5>Información Financiera</h5>
                                    <table class="table table-bordered">
                                        <tr>
                                            <th style="width: 40%;">Monto:</th>
                                            <td>
                                                @if($presupuesto->monto)
                                                    @if($presupuesto->moneda_id == 2 && $presupuesto->cotizacion)
                                                        {{ $presupuesto->moneda->simbolo }} {{ number_format($presupuesto->monto, 2, ',', '.') }}
                                                        <br>
                                                        <small class="text-muted">
                                                            Gs. {{ number_format($presupuesto->monto * $presupuesto->cotizacion, 0, ',', '.') }}
                                                        </small>
                                                    @elseif($presupuesto->moneda)
                                                        {{ $presupuesto->moneda->simbolo }} {{ number_format($presupuesto->monto, 2, ',', '.') }}
                                                    @else
                                                        {{ number_format($presupuesto->monto, 2, ',', '.') }}
                                                    @endif
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Moneda:</th>
                                            <td>{{ $presupuesto->moneda->nombre ?? 'Sin moneda' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Cotización:</th>
                                            <td>{{ $presupuesto->cotizacion ? number_format($presupuesto->cotizacion, 4, ',', '.') : '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Fecha de Aprobación:</th>
                                            <td>{{ $presupuesto->fecha ? \Carbon\Carbon::parse($presupuesto->fecha)->format('d/m/Y') : '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Usuario que Cargó:</th>
                                            <td>{{ $presupuesto->usuario->nombre ?? 'Sin usuario' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <!-- Archivos -->
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <h5>Archivos</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h6 class="card-title">Presupuesto (PDF)</h6>
                                                </div>
                                                <div class="card-body text-center">
                                                    @if($presupuesto->presupuesto)
                                                        <i class="fas fa-file-pdf fa-3x text-danger mb-3"></i>
                                                        <br>
                                                        <p class="mb-2">{{ $presupuesto->presupuesto }}</p>
                                                        <a href="{{ route('presupuestos.download-file', ['id' => $presupuesto->id, 'type' => 'presupuesto']) }}"
                                                           target="_blank" class="btn btn-primary btn-sm">
                                                            <i class="fas fa-eye"></i> Ver Archivo
                                                        </a>
                                                    @else
                                                        <i class="fas fa-file fa-3x text-muted mb-3"></i>
                                                        <br>
                                                        <p class="text-muted">No hay archivo cargado</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h6 class="card-title">Nota de Conformidad (PDF)</h6>
                                                </div>
                                                <div class="card-body text-center">
                                                    @if($presupuesto->conformidad)
                                                        <i class="fas fa-file-pdf fa-3x text-danger mb-3"></i>
                                                        <br>
                                                        <p class="mb-2">{{ $presupuesto->conformidad }}</p>
                                                        <a href="{{ route('presupuestos.download-file', ['id' => $presupuesto->id, 'type' => 'conformidad']) }}"
                                                           target="_blank" class="btn btn-primary btn-sm">
                                                            <i class="fas fa-eye"></i> Ver Archivo
                                                        </a>
                                                    @else
                                                        <i class="fas fa-file fa-3x text-muted mb-3"></i>
                                                        <br>
                                                        <p class="text-muted">No hay archivo cargado</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Información de Facturación (placeholder para futuras funcionalidades) -->
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <h5>Estado de Facturación</h5>
                                    <table class="table table-bordered">
                                        <tr>
                                            <th style="width: 20%;">Monto Facturado:</th>
                                            <td>-</td>
                                            <th style="width: 20%;">% Facturado:</th>
                                            <td>-</td>
                                        </tr>
                                        <tr>
                                            <th>Monto Cobrado:</th>
                                            <td>-</td>
                                            <th>% Cobrado:</th>
                                            <td>-</td>
                                        </tr>
                                        <tr>
                                            <th>Saldo a Facturar:</th>
                                            <td>-</td>
                                            <th>Saldo a Cobrar:</th>
                                            <td>-</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
    @include('partials.footer')
</body>
</html>
