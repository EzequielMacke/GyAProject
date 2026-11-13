<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UltrasonidoIndirectoTc extends Model
{
    use HasFactory;

    protected $table = 'ultrasonidos_indirectos_tc';

    protected $fillable = [
        'obra_tc_id',
        'usuario_id',
        'fecha',
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
        return $this->hasMany(UltrasonidoIndirectoDetalleTc::class, 'ultrasonido_indirecto_tc_id');
    }

    /**
     * Datos del reporte de la planilla de la obra (una fila por punto
     * ensayado). Lo usan el reporte PNG de Planillas y el generador de
     * informes.
     */
    public static function reporte(ObraTc $obraTc): array
    {
        $ultrasonido = static::with(['detalles' => function ($query) {
            $query->orderBy('id');
        }, 'detalles.nivel'])->where('obra_tc_id', $obraTc->id)->first();

        $puntos = $ultrasonido
            ? $ultrasonido->detalles->values()->map(function ($detalle, $i) {
                $velocidad = $detalle->promedio !== null ? round((float) $detalle->promedio) : null;

                return [
                    'id' => $detalle->id,
                    'nivel' => $detalle->nivel?->descripcion ?: '-',
                    'identificacion' => 'U'.($i + 1),
                    'elemento' => $detalle->elemento ?: '-',
                    'velocidad' => $velocidad !== null ? number_format($velocidad, 0, '', '.') : '-',
                    'compactacion' => static::compactacionHormigon($velocidad),
                ];
            })
            : collect();

        return [
            'obra' => $obraTc->descripcion,
            'puntos' => $puntos,
        ];
    }

    /** Calidad del hormigón según la velocidad de pulso ultrasónico (m/s). */
    public static function compactacionHormigon(?float $velocidad): string
    {
        if ($velocidad === null) {
            return '-';
        }

        return match (true) {
            $velocidad > 4500 => 'Excelente',
            $velocidad >= 3500 => 'Buena',
            $velocidad >= 3000 => 'Aceptable',
            $velocidad >= 2000 => 'Mala',
            default => 'Muy Mala',
        };
    }
}
