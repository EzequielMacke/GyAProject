
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Directorio de la Obra</title>
    @include('partials.head')
    <style>
        .titulo-box {
            background: #f4f6fb;
            border-radius: 1.1rem;
            border: 1.5px solid #e3e6ea;
            padding: 1.2rem 2rem 1.2rem 1.5rem;
            margin-bottom: 2.2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            box-sizing: border-box;
        }
        .titulo-box h1 {
            font-size: 1.6rem;
            font-weight: 700;
            color: #222;
            letter-spacing: 0.2px;
            margin: 0;
            text-align: left;
        }
        .directorio-table {
            width: 100%;
            max-width: 900px;
            margin: 0 auto;
            background: #fff;
            border-radius: 1rem;
            box-shadow: 0 2px 12px 0 rgba(40,40,40,0.09);
            border: 1.5px solid #e3e6ea;
            overflow: hidden;
        }
        .directorio-table th, .directorio-table td {
            padding: 0.9rem 1rem;
            text-align: left;
        }
        .directorio-table th {
            background: #f4f6fb;
            font-weight: 700;
            color: #222;
        }
        .directorio-table tr {
            border-bottom: 1px solid #e3e6ea;
        }
        .directorio-table tr:last-child {
            border-bottom: none;
        }
        .directorio-table td {
            color: #444;
        }
        .agregar-usuarios-box {
            background: #fff;
            border-radius: 1rem;
            box-shadow: 0 2px 12px 0 rgba(40,40,40,0.09);
            border: 1.5px solid #e3e6ea;
            padding: 1.5rem;
            margin-bottom: 2rem;
            width: 100%;
            max-width: 900px;
        }
        .select2-container--default .select2-selection--multiple {
            border-radius: 0.5rem;
            border: 1px solid #ced4da;
        }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        @include('partials.navbar')
        @include('partials.sidebar')
        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="titulo-box">
                        <h1>Agregar Usuarios al Directorio: {{ $obra->nombre ?? '-' }}</h1>
                        <a href="{{ route('directorio.index', $obra->id) }}" class="btn btn-light" title="Volver al directorio"><i class="fas fa-arrow-left mr-2"></i></a>
                    </div>
                </div>
            </div>
            <section class="content">
                <div class="container-fluid d-flex flex-column align-items-center" style="min-height:60vh;">
                    <div class="row w-100 justify-content-center mx-0">
                        <div class="col-12">
                            <div class="agregar-usuarios-box">
                                <h3>Agregar Usuarios al Directorio</h3>
                                <form action="{{ route('directorio.store', $obra->id) }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label for="usuarios">Seleccionar Usuarios:</label>
                                        <select name="usuarios[]" id="usuarios" class="form-control" multiple="multiple" required>
                                            @foreach($usuariosDisponibles as $usuario)
                                                <option value="{{ $usuario->id }}">{{ $usuario->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Agregar Usuarios</button>
                                </form>
                            </div>
                            <table class="directorio-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Usuario</th>
                                        <th>Fecha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($directorios as $directorio)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $directorio->usuario->nombre ?? '-' }}</td>
                                            <td>{{ $directorio->fecha }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center">No hay registros en el directorio.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        @include('partials.footer')
    </div>
    <script>
        $(document).ready(function() {
            $('#usuarios').select2({
                placeholder: 'Selecciona uno o más usuarios',
                allowClear: true,
                width: '100%'
            });
        });
    </script>
</body>
</html>

