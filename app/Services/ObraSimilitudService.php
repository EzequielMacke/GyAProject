<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Detecta obras con nombre parecido para evitar que se carguen duplicadas.
 *
 * La comparación ignora mayúsculas, tildes, signos de puntuación y palabras
 * genéricas ("Edificio", "Obra", "Vivienda"...), y tolera una letra de
 * diferencia por palabra (ej. "Cavallaro" / "Caballaro", "Salazar" / "Zalazar").
 */
class ObraSimilitudService
{
    /**
     * Palabras que no aportan a la identificación de la obra.
     * Se pasa también al JS de trabajo_campo/index.blade.php, que replica esta lógica.
     */
    public const PALABRAS_GENERICAS = [
        'a', 'al', 'con', 'de', 'del', 'el', 'en', 'la', 'las', 'lo', 'los', 'por', 'para', 'y', 'e', 'c',
        'obra', 'obras', 'edificio', 'edif', 'colegio', 'escuela', 'escuelas', 'club', 'banco', 'casa',
        'hospital', 'torre', 'centro', 'estadio', 'hotel', 'cooperativa', 'vivienda', 'viv', 'puente',
        'presupuesto', 'sa', 'srl', 'ing', 'arq', 'evaluacion', 'inspeccion', 'analisis', 'nueva', 'nuevo',
    ];

    /** Puntaje mínimo (0 a 1) para considerar dos nombres parecidos. */
    public const UMBRAL = 0.5;

    /**
     * Normaliza un nombre: minúsculas, sin tildes y solo letras/números separados por espacio.
     */
    public static function normalizar(string $nombre): string
    {
        $texto = Str::lower(Str::ascii($nombre));
        $texto = preg_replace('/[^a-z0-9]+/', ' ', $texto);

        return trim(preg_replace('/\s+/', ' ', $texto));
    }

    /**
     * Palabras significativas del nombre. Si todas son genéricas, se usan todas.
     */
    private static function palabras(string $nombre): array
    {
        $todas = array_values(array_filter(explode(' ', self::normalizar($nombre)), fn ($p) => $p !== ''));
        $significativas = array_values(array_diff($todas, self::PALABRAS_GENERICAS));

        return array_values(array_unique($significativas ?: $todas));
    }

    /**
     * Dos palabras coinciden si son iguales, una es prefijo de la otra
     * (mín. 4 letras) o difieren en una sola letra (palabras de 5+ letras).
     */
    private static function palabrasCoinciden(string $a, string $b): bool
    {
        if ($a === $b) {
            return true;
        }

        $corta = strlen($a) <= strlen($b) ? $a : $b;
        $larga = $corta === $a ? $b : $a;

        if (strlen($corta) >= 4 && str_starts_with($larga, $corta)) {
            return true;
        }

        return strlen($corta) >= 5 && levenshtein($a, $b) <= 1;
    }

    /**
     * Puntaje de similitud entre 0 y 1.
     */
    public static function puntaje(string $nombreA, string $nombreB): float
    {
        $normA = self::normalizar($nombreA);
        $normB = self::normalizar($nombreB);

        if ($normA === '' || $normB === '') {
            return 0;
        }

        if (str_replace(' ', '', $normA) === str_replace(' ', '', $normB)) {
            return 1;
        }

        $palabrasA = self::palabras($nombreA);
        $palabrasB = self::palabras($nombreB);

        $coincidencias = 0;
        foreach ($palabrasA as $pa) {
            foreach ($palabrasB as $pb) {
                if (self::palabrasCoinciden($pa, $pb)) {
                    $coincidencias++;
                    break;
                }
            }
        }

        return $coincidencias / max(1, min(count($palabrasA), count($palabrasB)));
    }

    /**
     * Devuelve el registro cuyo nombre es equivalente (mismo texto salvo
     * mayúsculas, tildes, espacios o signos), o null si no hay.
     */
    public static function existeIdentica(string $nombre, Builder $query, string $columna): ?Model
    {
        $buscado = str_replace(' ', '', self::normalizar($nombre));

        return $query->get()
            ->first(fn (Model $m) => str_replace(' ', '', self::normalizar((string) $m->{$columna})) === $buscado);
    }

    /**
     * Registros con nombre parecido, ordenados por similitud.
     * Cada elemento: ['modelo' => Model, 'puntaje' => float].
     */
    public static function buscar(string $nombre, Builder $query, string $columna, int $limite = 8): Collection
    {
        if (strlen(self::normalizar($nombre)) < 3) {
            return collect();
        }

        $normBuscado = self::normalizar($nombre);

        return $query->get()
            ->map(function (Model $m) use ($nombre, $normBuscado, $columna) {
                $valor = (string) $m->{$columna};
                similar_text($normBuscado, self::normalizar($valor), $parecido);

                return ['modelo' => $m, 'puntaje' => self::puntaje($nombre, $valor), 'parecido' => $parecido];
            })
            ->filter(fn ($item) => $item['puntaje'] >= self::UMBRAL)
            ->sortBy([['puntaje', 'desc'], ['parecido', 'desc']])
            ->take($limite)
            ->values();
    }
}
