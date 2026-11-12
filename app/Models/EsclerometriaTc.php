<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EsclerometriaTc extends Model
{
    use HasFactory;

    protected $table = 'esclerometrias_tc';

    protected $fillable = [
        'obra_tc_id',
        'usuario_id',
        'fecha',
        'lectura_inicial_yunque',
        'lectura_final_yunque',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    public function obra()
    {
        return $this->belongsTo(ObraTc::class, 'obra_tc_id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuarios::class);
    }

    public function detalles()
    {
        return $this->hasMany(EsclerometriaDetalleTc::class, 'esclerometria_tc_id');
    }

    /**
     * Datos del reporte de la planilla de la obra (una fila por punto
     * ensayado). Lo usan el reporte PNG de Planillas y el generador de
     * informes.
     */
    public static function reporte(ObraTc $obraTc): array
    {
        $esclerometria = static::with(['detalles' => function ($query) {
            $query->orderBy('id');
        }, 'detalles.nivel'])->where('obra_tc_id', $obraTc->id)->first();

        $puntos = $esclerometria
            ? $esclerometria->detalles->values()->map(function ($detalle, $i) {
                return [
                    'nivel' => $detalle->nivel?->descripcion ?: '-',
                    'identificacion' => 'E'.($i + 1),
                    'elemento' => $detalle->elemento ?: '-',
                    'direccion' => $detalle->direccion.'°',
                    'n_final' => $detalle->n_final !== null ? number_format(round((float) $detalle->n_final), 0, '', '.') : '-',
                ];
            })
            : collect();

        return [
            'obra' => $obraTc->descripcion,
            'fecha' => $esclerometria?->fecha?->format('d/m/Y'),
            'puntos' => $puntos,
        ];
    }
}
