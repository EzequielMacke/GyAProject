<?php

namespace App\Http\Controllers;

use App\Models\DirectorioTc;
use App\Models\ObraTc;
use App\Models\Plano;
use App\Models\PlanoGrupo;
use App\Models\PlanoSubgrupo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PlanoController extends Controller
{
    public function index(ObraTc $obraTc)
    {
        $usuarioId = session('usuario_id');

        $enDirectorio = DirectorioTc::where('obra_tc_id', $obraTc->id)
            ->where('usuario_id', $usuarioId)
            ->exists();

        if (! $enDirectorio) {
            return redirect()->route('home')->with('error', 'No tenés acceso a esta obra.');
        }

        $planos = Plano::where('obra_id', $obraTc->id)
            ->whereNotNull('descripcion')
            ->whereNotNull('subgrupo_id')
            ->with(['grupo', 'subgrupo', 'usuario'])
            ->orderBy('descripcion')
            ->get();

        $arbol = $planos->groupBy('grupo_id')
            ->map(function ($planosDelGrupo) {
                return [
                    'grupo' => $planosDelGrupo->first()->grupo,
                    'subgrupos' => $planosDelGrupo->groupBy('subgrupo_id')
                        ->map(function ($planosDelSubgrupo) {
                            return [
                                'subgrupo' => $planosDelSubgrupo->first()->subgrupo,
                                'planos' => $planosDelSubgrupo,
                            ];
                        })
                        ->sortBy('subgrupo.descripcion'),
                ];
            })
            ->sortBy('grupo.descripcion');

        $pendientesCount = Plano::where('obra_id', $obraTc->id)
            ->where(function ($query) {
                $query->whereNull('descripcion')->orWhereNull('subgrupo_id');
            })
            ->count();

        $gruposExistentes = PlanoGrupo::where('obra_id', $obraTc->id)
            ->orderBy('descripcion')
            ->pluck('descripcion')
            ->unique();

        return view('planos_tc.index', compact('obraTc', 'planos', 'arbol', 'pendientesCount', 'gruposExistentes'));
    }

    public function show(ObraTc $obraTc, Plano $plano)
    {
        return view('planos_tc.plano', compact('obraTc', 'plano'));
    }

    public function store(Request $request, ObraTc $obraTc)
    {
        $request->validate([
            'nombre_grupo' => 'required|string|max:255',
            'archivos' => 'required|array|min:1',
            'archivos.*' => 'required|file|mimes:pdf',
        ]);

        $usuarioId = session('usuario_id');

        $grupo = PlanoGrupo::create([
            'descripcion' => $request->nombre_grupo,
            'obra_id' => $obraTc->id,
            'usuario_id' => $usuarioId,
        ]);

        if (! Storage::disk('public')->exists('planos')) {
            Storage::disk('public')->makeDirectory('planos');
        }

        foreach ($request->file('archivos') as $archivo) {
            $ruta = $archivo->store('planos', 'public');
            $nombreArchivo = basename($ruta);

            Plano::create([
                'descripcion' => null,
                'obra_id' => $obraTc->id,
                'grupo_id' => $grupo->id,
                'subgrupo_id' => null,
                'archivo' => $nombreArchivo,
                'archivo_original' => $archivo->getClientOriginalName(),
                'usuario_id' => $usuarioId,
            ]);
        }

        return redirect()->route('planos_tc.index', $obraTc->id)->with('success', 'Planos cargados como pendientes de nombrar y clasificar.');
    }

    public function aprobar(ObraTc $obraTc)
    {
        $usuarioId = session('usuario_id');

        $enDirectorio = DirectorioTc::where('obra_tc_id', $obraTc->id)
            ->where('usuario_id', $usuarioId)
            ->exists();

        if (! $enDirectorio) {
            return redirect()->route('home')->with('error', 'No tenés acceso a esta obra.');
        }

        $pendientes = Plano::where('obra_id', $obraTc->id)
            ->where(function ($query) {
                $query->whereNull('descripcion')->orWhereNull('subgrupo_id');
            })
            ->with('grupo')
            ->orderBy('created_at')
            ->get();

        $subgruposObra = PlanoSubgrupo::where('obra_id', $obraTc->id)
            ->orderBy('descripcion')
            ->pluck('descripcion')
            ->unique()
            ->values();

        $nombresPlanosObra = Plano::where('obra_id', $obraTc->id)
            ->whereNotNull('descripcion')
            ->orderBy('descripcion')
            ->pluck('descripcion')
            ->unique()
            ->values();

        return view('planos_tc.aprobar', compact('obraTc', 'pendientes', 'subgruposObra', 'nombresPlanosObra'));
    }

    public function aprobarStore(Request $request, ObraTc $obraTc, Plano $plano)
    {
        $request->validate([
            'nombre' => [
                'required', 'string', 'max:255',
                Rule::unique('planos', 'descripcion')->where('obra_id', $obraTc->id)->ignore($plano->id),
            ],
            'subgrupo' => 'required|string|max:255',
        ], [
            'nombre.unique' => 'Ya existe un plano con ese nombre en esta obra.',
        ]);

        $subgrupo = PlanoSubgrupo::firstOrCreate(
            [
                'obra_id' => $obraTc->id,
                'grupo_id' => $plano->grupo_id,
                'descripcion' => $request->subgrupo,
            ],
            [
                'usuario_id' => session('usuario_id'),
            ]
        );

        $plano->update([
            'descripcion' => $request->nombre,
            'subgrupo_id' => $subgrupo->id,
        ]);

        return response()->json(['success' => true]);
    }
}
