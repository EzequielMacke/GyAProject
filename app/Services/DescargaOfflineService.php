<?php

namespace App\Services;

use App\Models\ObraTc;
use App\Models\Plano;
use Illuminate\Support\Facades\Storage;

/**
 * Arma la lista de URLs que hay que dejar guardadas en el dispositivo
 * para trabajar sin conexión (las descarga public/sw.js al recibir el
 * mensaje "precachear"). La usan los botones "Descargar para trabajar
 * sin conexión" de Planos, Planillas y Pachometrías, y el de la obra
 * entera (obras_tc/index): así los cuatro piden exactamente lo mismo y
 * no se desincronizan cuando se agrega una página o una librería.
 *
 * Cada método devuelve ['paginas' => [...], 'recursos' => [...], 'bytes' => int]:
 * las páginas van al cache de páginas del Service Worker y los recursos
 * (PDFs, JS, íconos, librerías de CDN) al de estáticos. "bytes" es una
 * estimación para avisar el tamaño antes de descargar la obra entera.
 */
class DescargaOfflineService
{
    /** Librerías de CDN del editor de planos (planos_tc/plano.blade.php). */
    public const PDFJS = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js';
    public const PDFJS_WORKER = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
    public const PDF_LIB = 'https://cdnjs.cloudflare.com/ajax/libs/pdf-lib/1.17.1/pdf-lib.min.js';
    public const JSZIP = 'https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js';

    /** Exportar PDF de Pachometrías (se cargan recién al exportar). */
    public const LIBRERIAS_PDF_PACHOMETRIA = [
        'https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js',
        'https://cdn.jsdelivr.net/npm/svg2pdf.js@2.2.3/dist/svg2pdf.umd.min.js',
    ];
    public const URL_FUENTES_PACHOMETRIA = 'https://cdn.jsdelivr.net/gh/tokotype/PlusJakartaSans@master/fonts/ttf/';
    public const FUENTES_PACHOMETRIA = [400 => 'Regular', 600 => 'SemiBold', 700 => 'Bold', 800 => 'ExtraBold'];

    /**
     * Peso aproximado de lo que no está en este servidor (no se puede
     * medir sin descargarlo). Sólo se usa para el aviso de tamaño.
     */
    private const BYTES_PAGINA = 80 * 1024;
    private const BYTES_CDN = [
        self::PDFJS => 320 * 1024,
        self::PDFJS_WORKER => 1100 * 1024,
        self::PDF_LIB => 520 * 1024,
        self::JSZIP => 100 * 1024,
    ];
    private const BYTES_CDN_DESCONOCIDO = 150 * 1024;

    private const RUTAS_PLANILLAS = [
        'planilla_tc.esclerometria',
        'planilla_tc.ultrasonido_indirecto',
        'planilla_tc.carbonatacion',
        'planilla_tc.cloruros',
        'planilla_tc.medicion_fisura',
        'planilla_tc.resistividad',
    ];

    /** Mismos planos que lista planos_tc.index: ya nombrados y clasificados. */
    public static function planosDeObra(ObraTc $obraTc)
    {
        return Plano::where('obra_id', $obraTc->id)
            ->whereNotNull('descripcion')
            ->whereNotNull('subgrupo_id')
            ->orderBy('descripcion')
            ->get();
    }

    public static function planos(ObraTc $obraTc, $planos = null): array
    {
        $planos ??= self::planosDeObra($obraTc);
        $disco = Storage::disk('public');

        $paginas = [route('planos_tc.index', $obraTc->id)];
        $recursos = [];
        $bytes = 0;

        foreach ($planos as $plano) {
            $paginas[] = route('planos_tc.plano', [$obraTc->id, $plano->id]);
            $recursos[] = Storage::url('planos/'.$plano->archivo);
            $rutaPdf = 'planos/'.$plano->archivo;
            if ($disco->exists($rutaPdf)) {
                $bytes += $disco->size($rutaPdf);
            }
        }

        /* Lo que necesita el editor para arrancar sin señal aunque nunca
           se haya abierto un plano con conexión: librerías, scripts
           propios e íconos de la barra de herramientas. */
        $recursos = array_merge($recursos, [self::PDFJS, self::PDFJS_WORKER, self::PDF_LIB, self::JSZIP]);
        $recursos = array_merge($recursos, self::assetsLocales(array_merge(
            ['js/plano-offline.js', 'js/plano-dxf.js'],
            self::iconosPlano()
        )));

        return self::conTamano($paginas, $recursos, $bytes);
    }

    public static function planillas(ObraTc $obraTc): array
    {
        $paginas = [route('planilla_tc.index', $obraTc->id)];
        foreach (self::RUTAS_PLANILLAS as $ruta) {
            $paginas[] = route($ruta, $obraTc->id);
        }

        return self::conTamano($paginas, self::assetsLocales(['js/planilla-offline.js']));
    }

    public static function pachometria(ObraTc $obraTc): array
    {
        $recursos = array_merge(
            self::assetsLocales(['js/planilla-offline.js']),
            self::LIBRERIAS_PDF_PACHOMETRIA,
            array_map(
                fn ($peso) => self::URL_FUENTES_PACHOMETRIA."PlusJakartaSans-{$peso}.ttf",
                array_values(self::FUENTES_PACHOMETRIA)
            )
        );

        return self::conTamano([route('pachometria_tc.index', $obraTc->id)], $recursos);
    }

    /**
     * La obra entera: la página de la obra más cada módulo que el
     * usuario tenga permiso de ver (la galería queda afuera a propósito:
     * puede pesar muchísimo).
     */
    public static function obra(ObraTc $obraTc): array
    {
        $permisos = app(PermisoService::class);
        $partes = [self::conTamano([route('obras_tc.index', $obraTc->id)], [])];

        if ($permisos->puede('pla_tc', 'ver')) $partes[] = self::planos($obraTc);
        if ($permisos->puede('ens_tc', 'ver')) $partes[] = self::planillas($obraTc);
        if ($permisos->puede('pch_tc', 'ver')) $partes[] = self::pachometria($obraTc);

        $paginas = array_values(array_unique(array_merge(...array_column($partes, 'paginas'))));
        $recursos = array_values(array_unique(array_merge(...array_column($partes, 'recursos'))));

        // Se recalcula sobre la lista sin repetidos (planilla-offline.js está en dos módulos).
        $bytesPdfs = 0;
        foreach ($partes as $parte) {
            $bytesPdfs += $parte['bytesPdfs'];
        }

        return self::conTamano($paginas, $recursos, $bytesPdfs);
    }

    private static function iconosPlano(): array
    {
        return array_map(
            fn ($archivo) => 'img/iconos/'.basename($archivo),
            glob(public_path('img/iconos/*.svg')) ?: []
        );
    }

    private static function assetsLocales(array $rutas): array
    {
        return array_map(fn ($ruta) => asset($ruta), $rutas);
    }

    /**
     * $bytesPdfs: lo ya medido en disco (los PDFs). A eso se le suma el
     * peso real de los archivos de public/ y una estimación para las
     * páginas y las librerías de CDN.
     */
    private static function conTamano(array $paginas, array $recursos, int $bytesPdfs = 0): array
    {
        $bytes = $bytesPdfs + count($paginas) * self::BYTES_PAGINA;
        $base = rtrim(asset(''), '/');

        foreach ($recursos as $url) {
            if (str_starts_with($url, $base.'/') && ! str_contains($url, '/storage/')) {
                $archivo = public_path(rawurldecode(substr($url, strlen($base) + 1)));
                $bytes += is_file($archivo) ? filesize($archivo) : 0;
            } elseif (! str_contains($url, '/storage/')) {
                $bytes += self::BYTES_CDN[$url] ?? self::BYTES_CDN_DESCONOCIDO;
            }
        }

        return [
            'paginas' => $paginas,
            'recursos' => $recursos,
            'bytes' => $bytes,
            'bytesPdfs' => $bytesPdfs,
        ];
    }
}
