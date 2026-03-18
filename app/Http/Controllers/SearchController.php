<?php

namespace App\Http\Controllers;

use App\Models\Contacto;
use App\Models\FacturaVenta;
use App\Models\Obra;
use App\Models\PresupuestoAprobado;
use App\Models\RecibidoVenta;
use App\Models\Tableta;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $q = trim($request->input('q', ''));

        if (strlen($q) < 2) {
            return response()->json([]);
        }

        $obras = Obra::where('nombre', 'like', "%$q%")
            ->orWhere('direccion', 'like', "%$q%")
            ->limit(5)->get()
            ->map(fn($o) => [
                'tipo'  => 'Obra',
                'icon'  => 'fa-hard-hat',
                'label' => $o->nombre,
                'sub'   => $o->direccion ?? '',
                'url'   => route('obras.show', $o->id),
            ]);

        $presupuestos = PresupuestoAprobado::where('clave', 'like', "%$q%")
            ->orWhere('orden_trabajo', 'like', "%$q%")
            ->orWhere('ubicacion', 'like', "%$q%")
            ->limit(5)->get()
            ->map(fn($p) => [
                'tipo'  => 'Presupuesto',
                'icon'  => 'fa-file-invoice-dollar',
                'label' => $p->clave ?? '#'.$p->id,
                'sub'   => $p->orden_trabajo ? 'OT: '.$p->orden_trabajo : '',
                'url'   => route('factura_venta.show', ['obraId' => $p->obra_id]),
            ]);

        $facturas = FacturaVenta::where('nro_factura', 'like', "%$q%")
            ->orWhere('concepto', 'like', "%$q%")
            ->orWhere('razon_social', 'like', "%$q%")
            ->limit(5)->get()
            ->map(fn($f) => [
                'tipo'  => 'Factura',
                'icon'  => 'fa-receipt',
                'label' => $f->nro_factura ?? '—',
                'sub'   => $f->concepto ?? '',
                'url'   => route('factura_venta.index', [
                    'presupuesto' => $f->presupuesto_aprobado_id,
                    'obra'        => $f->obra_id,
                ]),
            ]);

        $recibos = RecibidoVenta::where('nro_recibo', 'like', "%$q%")
            ->orWhere('concepto', 'like', "%$q%")
            ->limit(5)->get()
            ->map(fn($r) => [
                'tipo'  => 'Recibo',
                'icon'  => 'fa-money-bill-wave',
                'label' => $r->nro_recibo ?? '—',
                'sub'   => $r->concepto ?? '',
                'url'   => route('recibo_venta.index', [
                    'presupuesto' => $r->presupuesto_aprobado_id,
                    'obra'        => $r->obra_id,
                    'factura'     => $r->factura_id,
                ]),
            ]);

        $tabletas = Tableta::where('clave', 'like', "%$q%")
            ->orWhere('nombre', 'like', "%$q%")
            ->orWhere('modelo', 'like', "%$q%")
            ->orWhere('serie', 'like', "%$q%")
            ->limit(5)->get()
            ->map(fn($t) => [
                'tipo'  => 'Tableta',
                'icon'  => 'fa-tablet-alt',
                'label' => $t->nombre ?? $t->clave,
                'sub'   => implode(' · ', array_filter([$t->clave, $t->modelo])),
                'url'   => route('tabletas.index'),
            ]);

        $contactos = Contacto::where('nombre', 'like', "%$q%")
            ->orWhere('email', 'like', "%$q%")
            ->orWhere('telefono', 'like', "%$q%")
            ->limit(5)->get()
            ->map(fn($c) => [
                'tipo'  => 'Contacto',
                'icon'  => 'fa-address-book',
                'label' => $c->nombre,
                'sub'   => implode(' · ', array_filter([$c->telefono, $c->email])),
                'url'   => route('contacto.index', $c->obra_id),
            ]);

        $results = collect()
            ->merge($obras)
            ->merge($presupuestos)
            ->merge($facturas)
            ->merge($recibos)
            ->merge($tabletas)
            ->merge($contactos);

        return response()->json($results->values());
    }
}
