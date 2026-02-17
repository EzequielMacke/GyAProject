
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
        .titulo-box .acciones {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 0.7rem;
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
        .search-bar {
            border-radius: 1.2rem;
            border: 1px solid #ececec;
            padding-left: 1rem;
            font-size: 0.98rem;
            background: #fff;
            color: #222;
            box-shadow: none;
            transition: border 0.13s;
        }
        .search-bar:focus {
            border: 1.5px solid #bdbdbd;
            outline: none;
        }
        .agregar-usuario-btn {
            border-radius: 1.2rem;
            font-weight: 500;
            font-size: 0.98rem;
            padding: 0.38rem 1.1rem;
            background: #222;
            color: #fff;
            border: none;
            box-shadow: none;
            transition: background 0.13s;
        }
        .agregar-usuario-btn:hover {
            background: #444;
            color: #fff;
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
                        <h1>Directorio de la Obra: {{ $obra->nombre ?? '-' }}</h1>
                        <div class="acciones">
                            <input type="text" id="search" name="search" class="form-control search-bar" placeholder="Buscar usuarios..." style="min-width: 250px;">
                            <a href="{{ route('directorio.create', $obra->id) }}" class="btn agregar-usuario-btn">Agregar Usuarios</a>
                            <a href="{{ route('obras.show', ['id' => $obra->id]) }}" class="btn btn-light" title="Volver a la obra"><i class="fas fa-arrow-left"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <section class="content">
                <div class="container-fluid d-flex flex-column align-items-center" style="min-height:60vh;">
                    <div class="row w-100 justify-content-center mx-0">
                        <div class="col-12">
                            <table class="directorio-table" id="directorio-table">
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
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search');
            const tableRows = document.querySelectorAll('#directorio-table tbody tr');

            searchInput.addEventListener('input', function() {
                const searchTerm = searchInput.value.toLowerCase();

                tableRows.forEach(row => {
                    const cells = row.querySelectorAll('td');
                    const rowText = Array.from(cells).map(cell => cell.textContent.toLowerCase()).join(' ');
                    if (rowText.includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        });
    </script>
</body>
</html>

