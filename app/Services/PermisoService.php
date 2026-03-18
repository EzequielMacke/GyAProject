<?php

namespace App\Services;

use App\Models\Modulo;
use App\Models\Permiso;
use Illuminate\Support\Collection;

class PermisoService
{
    protected ?Collection $permisos = null;
    protected ?Collection $modulos  = null;

    /**
     * Carga permisos del área una sola vez por request.
     */
    protected function cargar(): void
    {
        if ($this->permisos !== null) return;

        $areaId = session('usuario_area_id');

        $this->modulos  = Modulo::all()->keyBy('id');
        $this->permisos = $areaId
            ? Permiso::where('area_id', $areaId)->get()->keyBy('modulo_id')
            : collect();
    }

    public function puede(string $modulo, string $accion = 'ver'): bool
    {
        $this->cargar();

        $moduloId = $this->modulos->first(fn($m) => $m->nombre === $modulo)?->id;

        if (!$moduloId) return false;

        $permiso = $this->permisos->get($moduloId);

        return $permiso && $permiso->$accion == 1;
    }
}
