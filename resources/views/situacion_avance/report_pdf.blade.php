<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Situación de Avance</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 14mm 12mm;
        }
        /* dompdf solo reaplica el margen del @page base en páginas que calzan con
           un selector adicional (:first, :left, :right, etc). ":first" deja sin
           margen a la página 2 en adelante; :left + :right cubren pares e impares,
           es decir todas las páginas. */
        @page :left {
            margin: 14mm 12mm;
        }
        @page :right {
            margin: 14mm 12mm;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Helvetica', Arial, sans-serif; font-size: 9px; color: #1e2835; }

        .header {
            border-bottom: 2px solid #2a6fdb;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }
        .header h1 { font-size: 16px; color: #1e2835; }
        .header p { font-size: 9px; color: #8496aa; margin-top: 3px; }

        table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        thead th {
            background: #edf1f6;
            color: #445060;
            font-size: 7.5px;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            text-align: left;
            padding: 5px 5px;
            border-bottom: 1.5px solid #d8e0ea;
            word-wrap: break-word;
        }
        tbody td {
            padding: 4px 5px;
            border-bottom: 1px solid #e4e9f0;
            font-size: 8px;
            word-wrap: break-word;
        }
        tbody tr:nth-child(even) { background: #f8fafc; }

        /* Anchos fijos por columna, sumando 100% */
        th:nth-child(1),  td:nth-child(1)  { width: 10%; } /* Clave */
        th:nth-child(2),  td:nth-child(2)  { width: 11%; } /* Obra */
        th:nth-child(3),  td:nth-child(3)  { width: 10%; } /* Tipo de trabajo */
        th:nth-child(4),  td:nth-child(4)  { width: 9%;  } /* Monto total */
        th:nth-child(5),  td:nth-child(5)  { width: 8%;  } /* Fecha inicio */
        th:nth-child(6),  td:nth-child(6)  { width: 7%;  } /* Plazo */
        th:nth-child(7),  td:nth-child(7)  { width: 8%;  } /* Fecha fin */
        th:nth-child(8),  td:nth-child(8)  { width: 12%; } /* Facturado */
        th:nth-child(9),  td:nth-child(9)  { width: 12%; } /* Cobrado */
        th:nth-child(10), td:nth-child(10) { width: 8%;  } /* Total gastos */
        th:nth-child(11), td:nth-child(11) { width: 5%;  } /* Estado */

        .td-clave { font-weight: bold; color: #1e2835; }
        .estado-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 8px;
            font-size: 7px;
            font-weight: bold;
            background: #e8f0fc;
            color: #2a6fdb;
        }
        .empty { text-align: center; color: #8496aa; padding: 20px; font-size: 10px; }

        tfoot td {
            padding: 6px 5px;
            font-size: 8.5px;
            font-weight: bold;
            color: #1e2835;
            border-top: 2px solid #2a6fdb;
            background: #e8f0fc;
        }
        .totales-label { text-align: right; }

        .footer { margin-top: 10px; font-size: 7.5px; color: #8496aa; text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte de Situación de Avance</h1>
        <p>Generado el {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
    </div>

    @if($filas->isEmpty())
        <p class="empty">No hay presupuestos que coincidan con los parámetros seleccionados.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Clave</th>
                    <th>Obra</th>
                    <th>Tipo de trabajo</th>
                    <th>Monto total</th>
                    <th>Fecha inicio</th>
                    <th>Plazo (días)</th>
                    <th>Fecha fin</th>
                    <th>Facturado</th>
                    <th>Cobrado</th>
                    <th>Total gastos</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($filas as $fila)
                    <tr>
                        <td class="td-clave">{{ $fila->presupuesto->clave }}</td>
                        <td>{{ $fila->presupuesto->obra?->nombre ?? '—' }}</td>
                        <td>{{ config('constantes.tipo_trabajo')[$fila->presupuesto->tipo_trabajo] ?? '—' }}</td>
                        <td>{{ $fila->monto > 0 ? 'Gs. ' . number_format($fila->monto, 0, ',', '.') : '—' }}</td>
                        <td>{{ $fila->avance?->fecha_inicio ? \Carbon\Carbon::parse($fila->avance->fecha_inicio)->format('d/m/Y') : '—' }}</td>
                        <td>{{ $fila->avance?->plazo ? $fila->avance->plazo . ' días' : '—' }}</td>
                        <td>{{ $fila->avance?->fecha_fin ? \Carbon\Carbon::parse($fila->avance->fecha_fin)->format('d/m/Y') : '—' }}</td>
                        <td>Gs. {{ number_format($fila->facturado, 0, ',', '.') }} ({{ $fila->pctFac }}%)</td>
                        <td>Gs. {{ number_format($fila->cobrado, 0, ',', '.') }} ({{ $fila->pctCob }}%)</td>
                        <td>Gs. {{ number_format($fila->totalGastos, 0, ',', '.') }}</td>
                        <td><span class="estado-badge">{{ $fila->estado }}</span></td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="7" class="totales-label">TOTALES</td>
                    <td>Gs. {{ number_format($totales['facturado'], 0, ',', '.') }}</td>
                    <td>Gs. {{ number_format($totales['cobrado'], 0, ',', '.') }}</td>
                    <td>Gs. {{ number_format($totales['totalGastos'], 0, ',', '.') }}</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
        <p class="footer">{{ $filas->count() }} resultado{{ $filas->count() != 1 ? 's' : '' }}</p>
    @endif
</body>
</html>
