<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear pedido para obra</title>
    @include('partials.head')
    <style>
        .select2-container .select2-selection--single {
            height: 45px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 45px;
        }
    </style>
    @php
        use App\Models\Modulo;
        use App\Models\Permiso;
        $permisos = Permiso::where('area_id', session('usuario_area_id'))->get();
    @endphp
    @if ($permisos->where('modulo_id', Modulo::where('nombre', 'ped_obr_ing')->first()->id ?? null)->where('agregar', 1)->isEmpty())
        <script>
            window.location.href = "{{ url('/home') }}";
        </script>
    @endif
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('#obra').select2({
                placeholder: 'Seleccione una obra',
                allowClear: true,
                width: '100%'
            });

            $('#obra').on('input', function() {
                var obraInput = $(this).val().toLowerCase();
                var obraExists = $('#obra option').filter(function() {
                    return $(this).text().toLowerCase() === obraInput;
                }).length > 0;

                if (obraExists || obraInput === '') {
                    $('#crear-obra-btn').prop('disabled', true);
                } else {
                    $('#crear-obra-btn').prop('disabled', false);
                }
            });

            var today = new Date();
            var day = String(today.getDate()).padStart(2, '0');
            var month = String(today.getMonth() + 1).padStart(2, '0');
            var year = today.getFullYear();
            var todayDate = year + '-' + month + '-' + day;
            document.getElementById('fecha_pedido').value = todayDate;

            $('#insumo').select2({
                placeholder: 'Seleccione un insumo',
                allowClear: true,
            });

            document.getElementById('add-insumo-btn').addEventListener('click', function(event) {
                event.preventDefault();
                var insumoSelect = document.getElementById('insumo');
                var insumoId = insumoSelect.value;
                var insumoText = insumoSelect.options[insumoSelect.selectedIndex].text;
                var insumoExists = Array.from(document.getElementsByName('insumo[]')).some(function(input) {
                    return input.value === insumoId;
                });

                if (insumoExists) {
                    alert('El insumo ya ha sido agregado.');
                    return;
                }

                if (insumoId !== '') {
                    var table = document.getElementById('insumos-table');
                    var newRow = table.insertRow(1);
                    var cell1 = newRow.insertCell(0);
                    var cell2 = newRow.insertCell(1);
                    var cell3 = newRow.insertCell(2);
                    var cell4 = newRow.insertCell(3);
                    var cell5 = newRow.insertCell(4);
                    var cell6 = newRow.insertCell(5);
                    cell1.textContent = table.rows.length - 1;
                    cell2.innerHTML = `<input type="hidden" name="insumo[]" value="${insumoId}">${insumoId}`;
                    cell3.textContent = insumoText;
                    cell4.innerHTML = `
                        <select name="unidad_medida[]" class="form-control">
                            @foreach (config('constantes.unidad_medida') as $codigo => $unidad)
                                <option value="{{ $codigo }}">{{ $unidad }}</option>
                            @endforeach
                        </select>`;
                    cell5.innerHTML = '<input type="number" name="cantidad[]" class="form-control" value="1" step="0.01">';
                    cell6.innerHTML = '<button type="button" class="btn btn-danger btn-sm remove-insumo-btn">Eliminar</button>';
                    insumoSelect.value = '';
                    $('#insumo').val(null).trigger('change');
                    var contadorInsumos = table.rows.length - 1;
                    document.getElementById('contador_insumos_input').textContent = contadorInsumos;
                    document.getElementById('contador_insumos_input').value = contadorInsumos;
                }
            });

            document.getElementById('insumos-table').addEventListener('click', function(event) {
                if (event.target.classList.contains('remove-insumo-btn')) {
                    event.target.closest('tr').remove();

                    var table = document.getElementById('insumos-table');
                    var contadorInsumos = table.rows.length - 1;
                    document.getElementById('contador_insumos_input').textContent = contadorInsumos;
                    document.getElementById('contador_insumos_input').value = contadorInsumos;

                    for (var i = 1; i < table.rows.length; i++) {
                        table.rows[i].cells[0].textContent = i;
                    }
                }
            });

            document.addEventListener('keydown', function(event) {
                if (event.ctrlKey && event.key === '2') {
                    event.preventDefault();
                    document.getElementById('volver-btn').click();
                }
            });

            var fechaEntregaInput = document.getElementById('fecha_entrega');
            var today = new Date();
            today.setDate(today.getDate() + 1);
            var day = ("0" + today.getDate()).slice(-2);
            var month = ("0" + (today.getMonth() + 1)).slice(-2);
            var year = today.getFullYear();
            fechaEntregaInput.value = year + "-" + month + "-" + day;
            fechaEntregaInput.addEventListener('change', function() {
                var fechaEntrega = new Date(this.value);
                var today = new Date();
                today.setHours(0, 0, 0, 0);
                if (fechaEntrega < today) {
                    alert('La fecha de entrega no puede ser menor a la fecha actual.');
                    this.value = '';
                }
            });

            document.getElementById('recargar').addEventListener('click', function() {
                fetch('{{ route('insumos.recargar') }}')
                    .then(response => response.json())
                    .then(data => {
                        var insumoSelect = document.getElementById('insumo');
                        insumoSelect.innerHTML = '';
                        data.forEach(insumo => {
                            var option = document.createElement('option');
                            option.value = insumo.id;
                            option.text = insumo.nombre;
                            insumoSelect.appendChild(option);
                        });
                        $('#insumo').val(null).trigger('change');
                    })
                    .catch(error => console.error('Error al recargar los insumos:', error));
            });

            document.getElementById('recargar_obras').addEventListener('click', function() {
                fetch('{{ route('obras.recargar') }}')
                    .then(response => response.json())
                    .then(data => {
                        var obraSelect = document.getElementById('obra');
                        obraSelect.innerHTML = '';
                        data.forEach(obra => {
                            var option = document.createElement('option');
                            option.value = obra.id;
                            option.text = obra.nombre;
                            obraSelect.appendChild(option);
                        });
                        $('#obra').val(null).trigger('change');
                    })
                    .catch(error => console.error('Error al recargar las obras:', error));
            });

            document.addEventListener('input', function(event) {
                if (event.target.name === 'cantidad[]') {
                    if (event.target.value <= 0) {
                        alert('La cantidad no puede ser menor o igual a 0.');
                        event.target.value = 1;
                    }
                }
            });
            var insumos = @json($insumos ?? []);
            var table = document.getElementById('insumos-table');
            insumos.forEach(function(insumo, index) {
                var newRow = table.insertRow(index + 1);
                var cell1 = newRow.insertCell(0);
                var cell2 = newRow.insertCell(1);
                var cell3 = newRow.insertCell(2);
                var cell4 = newRow.insertCell(3);
                var cell5 = newRow.insertCell(4);
                var cell6 = newRow.insertCell(5);
                cell1.textContent = index + 1;
                cell2.innerHTML = `<input type="hidden" name="insumo[]" value="${insumo.insumo_id}">${insumo.insumo_id}`;
                cell3.textContent = insumo.insumo.nombre;
                cell4.innerHTML = `
                    <select name="unidad_medida[]" class="form-control">
                        @foreach (config('constantes.unidad_medida') as $codigo => $unidad)
                            <option value="{{ $codigo }}" ${insumo.medida == '{{ $codigo }}' ? 'selected' : ''}>{{ $unidad }}</option>
                        @endforeach
                    </select>`;
                cell5.innerHTML = `<input type="number" name="cantidad[]" class="form-control" value="${insumo.cantidad}" step="0.01">`;
                cell6.innerHTML = '<button type="button" class="btn btn-danger btn-sm remove-insumo-btn">Eliminar</button>';
                var contadorInsumos = {{ $totalInsumos }};
                document.getElementById('contador_insumos_input').textContent = contadorInsumos;
                document.getElementById('contador_insumos_input').value = contadorInsumos;
            });
        });
    </script>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        @include('partials.navbar')
        @include('partials.sidebar')
        <div class="content-wrapper">
            <form action="{{ route('pedidobra.store') }}" method="POST">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Pedido para Obra</h1>
                        </div>
                        <div class="col-sm-6 text-right">
                            <button type="submit" class="btn btn-primary">Guardar</button>
                            <a href="{{ route('pedidobra.index') }}" class="btn btn-warning" id="volver-btn">Volver</a>
                        </div>
                    </div>
                </div>
            </div>
            <section class="content">
                <div class="container-fluid">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                        @csrf
                        <input type="hidden" name="contador_insumos" id="contador_insumos_input" value="0">
                        <div class="form-group row">
                            <label for="id_ped" class="col-sm-2 col-form-label text-center" >Nro. de Pedido</label>
                            <div class="col-sm-2">
                                <input type="hidden" name="id_ped" value="">
                                <input type="text" name="id_ped" class="form-control" id="id_ped" value="{{ $nuevoIdPedido }}" readonly>
                            </div>
                            <label for="user" class="col-sm-2 col-form-label text-center" >Creado por</label>
                            <div class="col-sm-2">
                                <input type="hidden" name="user_id" value="">
                                <input type="text" name="user" class="form-control" id="user" value="{{ session('usuario_nombre') }}" readonly>
                            </div>
                            <label for="fecha_pedido" class="col-sm-2 col-form-label text-center">Fecha de pedido</label>
                            <div class="col-sm-2">
                                <input type="date" name="fecha_pedido" class="form-control" id="fecha_pedido" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="obra" class="col-sm-2 col-form-label text-center">Nombre de la obra</label>
                            <div class="col-sm-4">
                                <select name="obra" class="form-control" id="obra">
                                    <option value="">Seleccione una obra</option>
                                    @foreach ($obras as $obra)
                                        <option value="{{ $obra->id }}">{{ $obra->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <button type="button" class="btn btn-secondary" id="recargar_obras">
                                    <i class="nav-icon fas fa-retweet"></i>
                                </button>
                            </div>
                            <label for="fecha_entrega" class="col-sm-2 col-form-label text-center">Fecha de entrega</label>
                            <div class="col-sm-2">
                                <input type="date" name="fecha_entrega" class="form-control" id="fecha_entrega">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="observacion" class="col-sm-2 col-form-label text-center">Observación</label>
                            <div class="col-sm-6">
                                <textarea name="observacion" class="form-control" id="observacion" rows="5"></textarea>
                            </div>
                        </div>
                        <div class="content-header">
                            <div class="container-fluid">
                                <div class="row mb-2">
                                    <div class="col-sm-6">
                                        <h1 class="m-0">Agregar Insumos</h1>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="insumo" class="col-sm-2 col-form-label text-center">Insumos</label>
                            <div class="col-sm-4">
                                <select name="insumo" class="form-control" id="insumo">
                                    <option value="">Seleccione un insumo</option>
                                    @foreach ($insumosDisponibles as $insumosDisponible)
                                        <option value="{{ $insumosDisponible->id }}">{{ $insumosDisponible->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <button type="button" class="btn btn-primary" id="add-insumo-btn">Añadir insumos a lista</button>
                            </div>
                            <div class="col-sm-2">
                                <button type="button" class="btn btn-secondary" id="recargar">
                                    <i class="nav-icon fas fa-retweet"></i>
                                </button>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <table class="table table-bordered" id="insumos-table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Código</th>
                                            <th>Insumo</th>
                                            <th>Unidad de Medida</th>
                                            <th>Cantidad</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </form>
                </div>
            </section>
        </div>
        @include('partials.footer')
    </div>
</body>
</html>
