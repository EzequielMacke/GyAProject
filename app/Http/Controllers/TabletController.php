<?php

namespace App\Http\Controllers;

use App\Models\Tableta;
use App\Models\TabletaUso;
use App\Models\Usuarios;
use Illuminate\Http\Request;

class TabletController extends Controller
{
    public function index()
    {
        $tabletas = Tableta::all();
        $tabletausos = TabletaUso::all();
        return view('tablet.index', compact('tabletas', 'tabletausos'));
    }

        public function create()
    {
        return view('tablet.create');
    }

    public function store(Request $request)
    {   
        Tableta::create([
            'clave' => $request->input('clave'),
            'nombre' => $request->input('nombre'),
            'modelo' => $request->input('modelo'),
            'serie' => $request->input('serie'),
            'sim' => $request->input('sim'),
            'estado' => 1,
            'observacion' => $request->input('observacion'),
        ]);
        return redirect()->route('tabletas.index')->with('success', 'Tableta agregada correctamente.');
    }

    public function generarQrs()
    {
        $tabletas = Tableta::all();
        foreach ($tabletas as $tableta) {
            // Generar la URL completa para el QR
            $baseUrl = config('app.url') ?? request()->getSchemeAndHttpHost();
            $qr = $baseUrl . '/tabletas/info/' . ($tableta->clave ?? $tableta->id);
            $tableta->codigo_qr = $qr;
            $tableta->save();
        }
        $qrs = Tableta::all();
        return view('tablet.generate',compact('qrs'));
    }

    public function info($clave)
    {
        $tableta   = Tableta::where('clave', $clave)->firstOrFail();
        $ultimoUso = TabletaUso::where('tableta_id', $tableta->id)
            ->orderBy('id', 'desc')
            ->first();

        $enUso   = $ultimoUso && $ultimoUso->aprobado == 1 && (!$ultimoUso->fecha_devolucion || !$ultimoUso->aprobacion_devolucion);
        $usuario = ($enUso && $ultimoUso->usuario_id) ? Usuarios::find($ultimoUso->usuario_id) : null;

        return view('tablet.info', compact('tableta', 'ultimoUso', 'enUso', 'usuario'));
    }

    public function assignShow($clave)
    {
        $tableta = Tableta::where('clave', $clave)->firstOrFail();
        $ultimoUso = TabletaUso::where('tableta_id', $tableta->id)
            ->orderBy('id', 'desc')
            ->first();
        if ($ultimoUso && !$ultimoUso->fecha_devolucion) {
            $usuarioRetiro = Usuarios::find($ultimoUso->usuario_id);
            return view('tablet.return', compact('tableta', 'usuarioRetiro'));
        } else {
            $usuarios = Usuarios::where('estado', 1)->get();
            return view('tablet.assign', compact('tableta', 'usuarios'));
        }
    }

    public function assignRetiro(Request $request, $clave)
    {
        $tableta = Tableta::where('clave', $clave)->firstOrFail();
        $usuarioId = $request->input('usuario');
        if (!$usuarioId) {
            return back()->withErrors(['usuario' => 'Debe seleccionar un usuario.']);
        }
        // Registrar el retiro
        $tabletaUso = new TabletaUso();
        $tabletaUso->tableta_id = $tableta->id;
        $tabletaUso->usuario_id = $usuarioId;
        $tabletaUso->fecha_retiro = now();
        $tabletaUso->save();
        return redirect()->route('tabletas.thanks')->with('success', 'Retiro registrado correctamente.');
    }

    public function devolucion(Request $request, $clave)
    {
        $tableta = Tableta::where('clave', $clave)->firstOrFail();
        $ultimoUso = TabletaUso::where('tableta_id', $tableta->id)
            ->orderBy('id', 'desc')
            ->first();
        if ($ultimoUso && !$ultimoUso->fecha_devolucion) {
            $ultimoUso->fecha_devolucion = now();
            $ultimoUso->save();
            return redirect()->route('tabletas.thanks')->with('success', 'Devolución registrada correctamente.');
        } else {
            return back()->withErrors(['devolucion' => 'No hay retiro pendiente para esta tableta.']);
        }
    }

    public function edit($id)
    {
        $tableta = Tableta::findOrFail($id);
        return view('tablet.edit', compact('tableta'));
    }

    public function update(Request $request, $id)
    {
        $tableta = Tableta::findOrFail($id);
        $tableta->update([
            'clave'       => $request->input('clave'),
            'nombre'      => $request->input('nombre'),
            'modelo'      => $request->input('modelo'),
            'serie'       => $request->input('serie'),
            'sim'         => $request->input('sim'),
            'observacion' => $request->input('observacion'),
        ]);
        return redirect()->route('tabletas.index')->with('success', 'Tableta actualizada correctamente.');
    }

    public function thanks()
    {
        return view('tablet.thanks');
    }

    public function retiro()
    {
        $usuarios    = Usuarios::where('estado', 1)->get();
        $tabletas    = Tableta::all();
        $tabletausos = TabletaUso::all();
        return view('tablet.retiro', compact('usuarios', 'tabletas', 'tabletausos'));
    }

    public function retiroStore(Request $request)
    {
        $request->validate([
            'asignaciones'                 => 'required|array|min:1',
            'asignaciones.*.usuario_id'    => 'required|exists:usuarios,id',
            'asignaciones.*.tableta_id'    => 'required|exists:tabletas,id',
        ]);

        foreach ($request->input('asignaciones') as $asignacion) {
            TabletaUso::create([
                'tableta_id'   => $asignacion['tableta_id'],
                'usuario_id'   => $asignacion['usuario_id'],
                'aprobado'     => 0,
                'fecha_retiro' => now(),
            ]);
        }

        return redirect()->route('tabletas.index')->with('success', 'Retiro(s) registrado(s) correctamente.');
    }

    public function devolucionIndex()
    {
        $tabletas    = Tableta::all();
        $tabletausos = TabletaUso::all();
        return view('tablet.devolucion', compact('tabletas', 'tabletausos'));
    }

    public function devolucionRegistrar($id)
    {
        $uso = TabletaUso::findOrFail($id);
        $uso->fecha_devolucion = now();
        $uso->save();
        return redirect()->route('tabletas.devolucion.index')->with('success', 'Devolución registrada, pendiente de aprobación.');
    }

    public function aprobacion()
    {
        $retiros = TabletaUso::with('tableta', 'usuario')
            ->where('aprobado', 0)
            ->whereNull('fecha_devolucion')
            ->get()
            ->each(function ($uso) {
                $uso->tipo = 'retiro';
            });

        $retiros->each(function ($uso) use ($retiros) {
            $uso->duplicado = $retiros->where('tableta_id', $uso->tableta_id)->count() > 1;
        });

        $devoluciones = TabletaUso::with('tableta', 'usuario')
            ->where('aprobado', 1)
            ->whereNotNull('fecha_devolucion')
            ->where('aprobacion_devolucion', 0)
            ->get()
            ->each(function ($uso) {
                $uso->tipo = 'devolucion';
            });

        $usos = $retiros->concat($devoluciones)
            ->sortByDesc(function ($uso) {
                return $uso->tipo === 'devolucion' ? $uso->fecha_devolucion : $uso->fecha_retiro;
            })
            ->values();

        return view('tablet.aprobacion', compact('usos'));
    }

    public function aprobar($id)
    {
        $uso = TabletaUso::findOrFail($id);

        $enUso = TabletaUso::where('tableta_id', $uso->tableta_id)
            ->where('id', '!=', $uso->id)
            ->where('aprobado', 1)
            ->where(function ($q) {
                $q->whereNull('fecha_devolucion')->orWhere('aprobacion_devolucion', 0);
            })
            ->exists();

        if ($enUso) {
            return redirect()->route('tabletas.aprobacion')
                ->with('error', 'No se puede aprobar: la tableta ya está en uso por otro retiro aprobado.');
        }

        $uso->aprobado = 1;
        $uso->save();
        return redirect()->route('tabletas.aprobacion')->with('success', 'Retiro aprobado correctamente.');
    }

    public function denegar($id)
    {
        $uso = TabletaUso::findOrFail($id);
        $uso->delete();
        return redirect()->route('tabletas.aprobacion')->with('success', 'Retiro denegado y eliminado.');
    }

    public function aprobarDevolucion($id)
    {
        $uso = TabletaUso::findOrFail($id);
        $uso->aprobacion_devolucion = 1;
        $uso->save();
        return redirect()->route('tabletas.aprobacion')->with('success', 'Devolución aprobada correctamente.');
    }

    public function denegarDevolucion($id)
    {
        $uso = TabletaUso::findOrFail($id);
        $uso->fecha_devolucion = null;
        $uso->save();
        return redirect()->route('tabletas.aprobacion')->with('success', 'Devolución denegada. La tableta sigue en uso.');
    }

    public function report(Request $request)
        {
            $tabletas = Tableta::all();
            $query = TabletaUso::with('tableta', 'usuario');
            if ($request->filled('tableta_id')) {
                $query->where('tableta_id', $request->tableta_id);
            }
            if ($request->filled('estado')) {
                switch ($request->estado) {
                    case 'pendiente_retiro':
                        $query->where('aprobado', 0);
                        break;
                    case 'en_uso':
                        $query->where('aprobado', 1)->whereNull('fecha_devolucion');
                        break;
                    case 'pendiente_devolucion':
                        $query->where('aprobado', 1)->whereNotNull('fecha_devolucion')->where('aprobacion_devolucion', 0);
                        break;
                    case 'finalizado':
                        $query->where('aprobado', 1)->where('aprobacion_devolucion', 1);
                        break;
                }
            }
            $usos = $query->orderBy('fecha_retiro', 'desc')->get();
            return view('tablet.report', compact('usos', 'tabletas'));
        }
    
}
