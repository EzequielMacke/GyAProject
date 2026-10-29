<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

/**
 * Genera y ubica las miniaturas de las fotos de los planos. La galería
 * muestra cientos de fotos de varios MB cada una: cargar los originales
 * sólo para pintar un cuadradito hacía lenta la carga y el scroll, así
 * que la grilla usa una versión chica en JPG guardada al lado del
 * original (fotos_planos/{plano}/miniaturas/{nombre}.jpg).
 */
class MiniaturaFotoService
{
    private const LADO_MAXIMO = 480;
    private const CALIDAD_JPG = 72;

    /** Convierte la URL pública guardada en la BD ("/storage/...") en ruta del disco public. */
    public static function rutaRelativa(string $archivo): string
    {
        return ltrim(str_replace('/storage/', '', $archivo), '/');
    }

    public static function rutaMiniatura(string $archivo): string
    {
        $ruta = self::rutaRelativa($archivo);

        return dirname($ruta).'/miniaturas/'.pathinfo($ruta, PATHINFO_FILENAME).'.jpg';
    }

    public static function existe(string $archivo): bool
    {
        return Storage::disk('public')->exists(self::rutaMiniatura($archivo));
    }

    public static function url(string $archivo): string
    {
        return Storage::url(self::rutaMiniatura($archivo));
    }

    /**
     * Devuelve la ruta relativa de la miniatura (generándola si hace falta)
     * o null si el original no existe o GD no lo puede leer.
     */
    public static function generar(string $archivo): ?string
    {
        $disco = Storage::disk('public');
        $rutaOriginal = self::rutaRelativa($archivo);
        $rutaMiniatura = self::rutaMiniatura($archivo);

        if ($disco->exists($rutaMiniatura)) {
            return $rutaMiniatura;
        }
        if (! $disco->exists($rutaOriginal)) {
            return null;
        }

        $origen = @imagecreatefromstring($disco->get($rutaOriginal));
        if (! $origen) {
            return null;
        }

        $origen = self::corregirOrientacion($origen, $disco->path($rutaOriginal));

        $ancho = imagesx($origen);
        $alto = imagesy($origen);
        $escala = min(1, self::LADO_MAXIMO / max($ancho, $alto));
        $nuevoAncho = max(1, (int) round($ancho * $escala));
        $nuevoAlto = max(1, (int) round($alto * $escala));

        // Fondo blanco para que los PNG con transparencia no queden negros al pasar a JPG.
        $miniatura = imagecreatetruecolor($nuevoAncho, $nuevoAlto);
        imagefill($miniatura, 0, 0, imagecolorallocate($miniatura, 255, 255, 255));
        imagecopyresampled($miniatura, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);
        imagedestroy($origen);

        ob_start();
        imagejpeg($miniatura, null, self::CALIDAD_JPG);
        $contenido = ob_get_clean();
        imagedestroy($miniatura);

        $disco->put($rutaMiniatura, $contenido);

        return $rutaMiniatura;
    }

    /**
     * GD descarta los metadatos EXIF, así que una foto de celular sacada
     * "de costado" quedaría girada en la miniatura aunque el navegador
     * muestre bien el original.
     */
    private static function corregirOrientacion(\GdImage $imagen, string $rutaAbsoluta): \GdImage
    {
        if (! function_exists('exif_read_data')) {
            return $imagen;
        }

        $exif = @exif_read_data($rutaAbsoluta);
        $orientacion = $exif['Orientation'] ?? 1;

        $angulo = match ((int) $orientacion) {
            3 => 180,
            6 => -90,
            8 => 90,
            default => 0,
        };

        if ($angulo === 0) {
            return $imagen;
        }

        $rotada = imagerotate($imagen, $angulo, 0);
        imagedestroy($imagen);

        return $rotada;
    }
}
