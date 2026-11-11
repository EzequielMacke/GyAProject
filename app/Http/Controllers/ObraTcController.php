<?php

namespace App\Http\Controllers;

use App\Models\DirectorioTc;
use App\Models\DirectorioTcAutomatico;
use App\Models\ObraTc;
use App\Models\Plano;
use App\Models\Usuarios;
use App\Services\DescargaOfflineService;
use App\Services\ObraSimilitudService;
use App\Services\PermisoService;
use Illuminate\Http\Request;

class ObraTcController extends Controller
{
    public function index()
    {
        $usuarioId = session('usuario_id');

        $obrasTc = ObraTc::whereHas('directorios', function ($query) use ($usuarioId) {
                $query->where('usuario_id', $usuarioId);
            })
            ->where('estado', '!=', 2)
            ->orderByDesc('id')
            ->get();

        $puedeGestionarAutomatico = app(PermisoService::class)->puede('tra_cam', 'eliminar');
        $usuariosDisponibles = collect();
        $usuariosAutomaticos = collect();

        if ($puedeGestionarAutomatico) {
            $usuariosDisponibles = Usuarios::where('estado', 1)
                ->where('id', '!=', 1)
                ->orderBy('nombre')
                ->get();

            $usuariosAutomaticos = DirectorioTcAutomatico::pluck('usuario_id');
        }

        $obrasSimilitud = app(PermisoService::class)->puede('obr_tc', 'agregar')
            ? $this->obrasParaSimilitud()
            : [];

        return view('trabajo_campo.index', compact('obrasTc', 'usuariosDisponibles', 'usuariosAutomaticos', 'obrasSimilitud'));
    }

    public function show($id)
    {
        $obraTc = ObraTc::with('usuario')->findOrFail($id);

        $usuarioId = session('usuario_id');

        $enDirectorio = DirectorioTc::where('obra_tc_id', $obraTc->id)
            ->where('usuario_id', $usuarioId)
            ->exists();

        if (! $enDirectorio) {
            return redirect()->route('home')->with('error', 'No tenés acceso a esta obra.');
        }

        if ($obraTc->estado == 2) {
            return redirect()->route('trabajo_campo.index')->with('error', 'Esta obra fue eliminada.');
        }

        $pendientesPlanos = Plano::where('obra_id', $obraTc->id)
            ->where(function ($query) {
                $query->whereNull('descripcion')->orWhereNull('subgrupo_id');
            })
            ->count();

        // Botón "Descargar obra para trabajar sin conexión" (planos, planillas y pachometrías).
        $descargaOffline = DescargaOfflineService::obra($obraTc);

        return view('obras_tc.index', compact('obraTc', 'pendientesPlanos', 'descargaOffline'));
    }

    /** Obras no eliminadas, con su creador. */
    private function queryObrasVigentes()
    {
        return ObraTc::where('estado', '!=', 2)
            ->with('usuario:id,nombre,nombre_completo')
            ->select(['id', 'descripcion', 'estado', 'usuario_id', 'created_at']);
    }

    /**
     * Todas las obras vigentes (de todos los usuarios) para detectar duplicados
     * en el navegador mientras se escribe el nombre de una obra nueva.
     */
    private function obrasParaSimilitud(): array
    {
        $accesibles = DirectorioTc::where('usuario_id', session('usuario_id'))->pluck('obra_tc_id')->flip();

        return $this->queryObrasVigentes()->get()->map(function (ObraTc $obra) use ($accesibles) {
            $tieneAcceso = $accesibles->has($obra->id);

            return [
                'id'      => $obra->id,
                'nombre'  => $obra->descripcion,
                'creador' => $obra->usuario->nombre_completo ?? $obra->usuario->nombre ?? '—',
                'fecha'   => optional($obra->created_at)->format('d/m/Y'),
                'acceso'  => $tieneAcceso,
                'url'     => $tieneAcceso ? route('obras_tc.index', $obra->id) : null,
            ];
        })->all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        // Mismo nombre salvo mayúsculas, tildes, espacios o signos: se bloquea.
        $identica = ObraSimilitudService::existeIdentica($request->nombre, $this->queryObrasVigentes(), 'descripcion');
        if ($identica) {
            return back()->withInput()->withErrors([
                'nombre' => "Ya existe la obra \"{$identica->descripcion}\" (creada por "
                    . ($identica->usuario->nombre_completo ?? $identica->usuario->nombre ?? '—')
                    . "). Pedile que te agregue a su directorio.",
            ]);
        }

        // Nombres parecidos: se exige confirmar que es una obra distinta.
        if (! $request->boolean('confirmar_distinta')) {
            $similares = ObraSimilitudService::buscar($request->nombre, $this->queryObrasVigentes(), 'descripcion');
            if ($similares->isNotEmpty()) {
                return back()->withInput()
                    ->withErrors([
                        'nombre' => 'Hay obras con nombre parecido. Revisá la lista y, si es una obra distinta, marcá la confirmación.',
                    ]);
            }
        }

        $usuarioId = session('usuario_id');

        $obraTc = ObraTc::create([
            'descripcion' => $request->nombre,
            'estado'      => 1,
            'usuario_id'  => $usuarioId,
        ]);

        DirectorioTc::create([
            'obra_tc_id' => $obraTc->id,
            'usuario_id' => $usuarioId,
        ]);

        if ($usuarioId != 1) {
            DirectorioTc::create([
                'obra_tc_id' => $obraTc->id,
                'usuario_id' => 1,
            ]);
        }

        $automaticos = DirectorioTcAutomatico::pluck('usuario_id');
        foreach ($automaticos as $autoUsuarioId) {
            if (in_array($autoUsuarioId, [$usuarioId, 1])) continue;

            DirectorioTc::create([
                'obra_tc_id' => $obraTc->id,
                'usuario_id' => $autoUsuarioId,
            ]);
        }

        return redirect()->route('trabajo_campo.index')->with('success', 'Obra creada correctamente.');
    }

    public function update(Request $request, ObraTc $obraTc)
    {
        $request->validate([
            'nombre'  => 'required|string|max:255',
            'mensaje' => 'nullable|string',
        ]);

        $obraTc->update([
            'descripcion' => $request->nombre,
            'mensaje'     => $request->mensaje,
        ]);

        return redirect()->route('obras_tc.index', $obraTc->id)->with('success', 'Obra actualizada correctamente.');
    }

    public function destroy(ObraTc $obraTc)
    {
        $obraTc->update(['estado' => 2]);

        return redirect()->route('trabajo_campo.index')->with('success', 'Obra eliminada correctamente.');
    }
}
