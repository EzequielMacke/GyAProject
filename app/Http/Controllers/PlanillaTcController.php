<?php

namespace App\Http\Controllers;

use App\Models\DirectorioTc;
use App\Models\ObraTc;

class PlanillaTcController extends Controller
{
    public function esclerometria(ObraTc $obraTc)
    {
        $usuarioId = session('usuario_id');

        $enDirectorio = DirectorioTc::where('obra_tc_id', $obraTc->id)
            ->where('usuario_id', $usuarioId)
            ->exists();

        if (! $enDirectorio) {
            return redirect()->route('home')->with('error', 'No tenés acceso a esta obra.');
        }

        return view('planilla_tc.esclerometria_pla', compact('obraTc'));
    }
}
