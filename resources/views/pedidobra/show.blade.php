<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle del Pedido para Obra</title>
    @include('partials.head')
    <script>
        document.addEventListener('keydown', function(event) {
                if (event.ctrlKey && event.key === '2') {
                    event.preventDefault();
                    document.getElementById('volver-btn').click();
                }
            });
    </script>
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
                            <h1 class="m-0">Detalle del Pedido para Obra</h1>
                        </div>
                        <div class="col-sm-6 text-right">
                            <a href="{{ route('pedidobra.index') }}" class="btn btn-warning" id="volver-btn">Volver</a>
                        </div>
                    </div>
                </div>
            </div>
            <section class="content">
                <div class="container-fluid">
                    <div class="form-group row">
                        <label for="id_ped" class="col-sm-2 col-form-label text-center">Nro. de Pedido</label>
                        <div class="col-sm-2">
                            <input type="text" name="id_ped" class="form-control" id="id_ped" value="{{ $pedido->id }}" readonly>
                        </div>
                        <label for="user" class="col-sm-2 col-form-label text-center">Creado por</label>
                        <div class="col-sm-2">
                            <input type="text" name="user" class="form-control" id="user" value="{{ $pedido->usuario->nombre }}" readonly>
                        </div>
                        <label for="fecha_pedido" class="col-sm-2 col-form-label text-center">Fecha de pedido</label>
                        <div class="col-sm-2">
                            <input type="date" name="fecha_pedido" class="form-control" id="fecha_pedido" value="{{ $pedido->fecha_pedido }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="obra" class="col-sm-2 col-form-label text-center">Nombre de la obra</label>
                        <div class="col-sm-4">
                            <input type="text" name="obra" class="form-control" id="obra" value="{{ $pedido->obra->nombre }}" readonly>
                        </div>
                        <label for="fecha_entrega" class="col-sm-2 col-form-label text-center">Fecha de entrega</label>
                        <div class="col-sm-4">
                            <input type="date" name="fecha_entrega" class="form-control" id="fecha_entrega" value="{{ $pedido->fecha_entrega }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="observacion" class="col-sm-2 col-form-label text-center">Observación</label>
                        <div class="col-sm-10">
                            <textarea name="observacion" class="form-control" id="observacion" rows="5" readonly>{{ $pedido->observacion }}</textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Código</th>
                                        <th>Insumo</th>
                                        <th>Unidad de Medida</th>
                                        <th>Cantidad</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pedido->detalles as $detalle)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $detalle->insumo_id }}</td>
                                            <td>{{ $detalle->insumo->nombre }}</td>
                                            <td>{{ config('constantes.unidad_medida')[$detalle->medida] }}</td>
                                            <td>{{ $detalle->cantidad }}</td>
                                            <td>
                                                <button class="btn btn-{{ $estados_label[$detalle->confirmado] }}">
                                                    {{ $estados[$detalle->confirmado] ?? 'Desconocido' }}
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        @include('partials.footer')
    </div>
</body>
</html>
