<?php

namespace App\Http\Controllers;

use App\Models\DirectorioTc;
use App\Models\ObraTc;
use App\Models\PachometriaTc;
use App\Models\Usuarios;
use App\Services\PermisoService;
use Illuminate\Http\Request;

class PachometriaTcController extends Controller
{
    public function index(ObraTc $obraTc)
    {
        if (! $this->tieneAccesoAObra($obraTc)) {
            return redirect()->route('home')->with('error', 'No tenés acceso a esta obra.');
        }

        // Lo que necesita el frontend para reconstruir cada tarjeta.
        $pachometrias = PachometriaTc::with('usuario')
            ->where('obra_tc_id', $obraTc->id)
            ->orderBy('orden')
            ->orderBy('id')
            ->get()
            ->map(fn (PachometriaTc $p) => [
                'id' => $p->id,
                'uid' => $p->uid,
                'datos' => $p->datos ?? [],
            ] + $this->autoria($p))
            ->values();

        $permisoService = app(PermisoService::class);
        $puedeAgregar = $permisoService->puede('pch_tc', 'agregar');
        $puedeEditar = $permisoService->puede('pch_tc', 'editar');
        $puedeEliminar = $permisoService->puede('pch_tc', 'eliminar');

        // Para mostrar la autoría de las tarjetas creadas sin conexión.
        $usuarioActual = Usuarios::find(session('usuario_id'));
        $nombreUsuarioActual = $usuarioActual ? ($usuarioActual->nombre_completo ?: $usuarioActual->nombre) : null;

        return view('pachometria_tc.index', compact('obraTc', 'pachometrias', 'puedeAgregar', 'puedeEditar', 'puedeEliminar', 'nombreUsuarioActual'));
    }

    /* Guardado por uid (lo genera el dispositivo al agregar la tarjeta): crea
       la pachometría si no existe o la reemplaza si ya existe. Es lo que usa
       la pantalla, también sin conexión (public/js/planilla-offline.js):
       como no depende de un id del servidor, una tarjeta creada offline se
       puede seguir editando y se sube entera al volver la señal, y reenviar
       el mismo guardado nunca la duplica. Crear exige 'agregar' y modificar
       exige 'editar'. */
    public function guardarPorUid(Request $request, ObraTc $obraTc, string $uid)
    {
        if (! $this->tieneAccesoAObra($obraTc)) {
            return response()->json(['message' => 'No tenés acceso a esta obra.'], 403);
        }

        $pachometria = PachometriaTc::where('uid', $uid)->first();

        if ($pachometria && (int) $pachometria->obra_tc_id !== (int) $obraTc->id) {
            return response()->json(['message' => 'No tenés acceso a esta pachometría.'], 403);
        }

        if (! app(PermisoService::class)->puede('pch_tc', $pachometria ? 'editar' : 'agregar')) {
            return response()->json(['message' => 'No tenés permiso para guardar esta pachometría.'], 403);
        }

        $columnas = $this->columnas($this->validar($request));

        if ($pachometria) {
            $pachometria->update($columnas);
        } else {
            $pachometria = PachometriaTc::create($columnas + [
                'uid' => $uid,
                'obra_tc_id' => $obraTc->id,
                'usuario_id' => session('usuario_id'),
            ]);
        }

        return response()->json(['ok' => true, 'id' => $pachometria->id] + $this->autoria($pachometria));
    }

    // Eliminar por uid. Si no existe (nunca llegó a subirse) no hay nada que borrar.
    public function eliminarPorUid(ObraTc $obraTc, string $uid)
    {
        if (! $this->tieneAccesoAObra($obraTc)) {
            return response()->json(['message' => 'No tenés acceso a esta obra.'], 403);
        }

        $pachometria = PachometriaTc::where('uid', $uid)->first();

        if ($pachometria && (int) $pachometria->obra_tc_id !== (int) $obraTc->id) {
            return response()->json(['message' => 'No tenés acceso a esta pachometría.'], 403);
        }

        $pachometria?->delete();

        return response()->json(['ok' => true]);
    }

    // Se llama apenas se agrega una tarjeta, para tener su id y poder
    // ir guardando los cambios con update().
    public function store(Request $request, ObraTc $obraTc)
    {
        if (! $this->tieneAccesoAObra($obraTc)) {
            return response()->json(['message' => 'No tenés acceso a esta obra.'], 403);
        }

        $data = $this->validar($request);

        $pachometria = PachometriaTc::create($this->columnas($data) + [
            'obra_tc_id' => $obraTc->id,
            'usuario_id' => session('usuario_id'),
        ]);

        return response()->json(['id' => $pachometria->id] + $this->autoria($pachometria), 201);
    }

    // Autoguardado: recibe la tarjeta completa y la reemplaza.
    public function update(Request $request, ObraTc $obraTc, PachometriaTc $pachometria)
    {
        if (! $this->tieneAccesoAObra($obraTc) || (int) $pachometria->obra_tc_id !== (int) $obraTc->id) {
            return response()->json(['message' => 'No tenés acceso a esta pachometría.'], 403);
        }

        $pachometria->update($this->columnas($this->validar($request)));

        return response()->json(['ok' => true]);
    }

    public function destroy(ObraTc $obraTc, PachometriaTc $pachometria)
    {
        if (! $this->tieneAccesoAObra($obraTc) || (int) $pachometria->obra_tc_id !== (int) $obraTc->id) {
            return response()->json(['message' => 'No tenés acceso a esta pachometría.'], 403);
        }

        $pachometria->delete();

        return response()->json(['ok' => true]);
    }

    private function validar(Request $request): array
    {
        return $request->validate([
            'datos' => 'present|array',
            'datos.campos' => 'nullable|array',
            'datos.campos.*' => 'nullable|string|max:255',
            'datos.listas' => 'nullable|array',
            'datos.listas.*' => 'array',
            'datos.listas.*.*' => 'array',
            'datos.listas.*.*.*' => 'nullable|string|max:50',
        ]);
    }

    /* Además del JSON completo, se copian a columnas propias los datos
       que sirven para ordenar y listar sin abrir el JSON. */
    private function columnas(array $data): array
    {
        $campos = $data['datos']['campos'] ?? [];
        $entero = fn ($valor) => is_numeric($valor) && (int) $valor > 0 ? (int) $valor : null;
        $tipo = $campos['tipo'] ?? null;

        return [
            'orden' => $entero($campos['idx'] ?? null),
            'numero' => $entero($campos['numero'] ?? null),
            'tipo' => in_array($tipo, ['viga', 'pilar', 'losa'], true) ? $tipo : null,
            'elemento' => isset($campos['elemento']) ? mb_substr(trim($campos['elemento']), 0, 60) ?: null : null,
            'datos' => $data['datos'],
        ];
    }

    // Quién la dibujó y cuándo, para mostrarlo en la cabecera de la tarjeta.
    private function autoria(PachometriaTc $p): array
    {
        $usuario = $p->usuario;

        return [
            'usuario' => $usuario ? ($usuario->nombre_completo ?: $usuario->nombre) : null,
            'creado' => $p->created_at?->format('d/m/Y H:i'),
        ];
    }

    private function tieneAccesoAObra(ObraTc $obraTc): bool
    {
        return DirectorioTc::where('obra_tc_id', $obraTc->id)
            ->where('usuario_id', session('usuario_id'))
            ->exists();
    }
}
