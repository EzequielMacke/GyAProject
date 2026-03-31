<?php

namespace App\Http\Controllers;

use App\Models\Insumo;
use App\Models\Kit;
use App\Models\KitDetalle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KitController extends Controller
{
	public function index()
	{
		$kits = Kit::with(['usuario', 'detalles.insumo'])->where('estado', 1)->get();
		return view('kits.index', compact('kits'));
	}

	public function create()
	{
		$insumos = Insumo::where('estado', 1)->orderBy('nombre')->get();
		return view('kits.create', compact('insumos'));
	}

	public function store(Request $request)
	{
		$request->validate([
			'descripcion' => 'required|string|max:255',
			'detalle' => 'required|json',
		]);

		$detalle = json_decode($request->detalle, true);

		if (empty($detalle)) {
			return back()->withErrors(['detalle' => 'Debe agregar al menos un insumo al kit.'])->withInput();
		}

		DB::transaction(function () use ($request, $detalle) {
			$kit = Kit::create([
				'descripcion' => $request->descripcion,
				'fecha_creacion' => now()->toDateString(),
				'usuario_id' => session('usuario_id'),
				'estado' => 1,
			]);

			foreach ($detalle as $item) {
				KitDetalle::create([
					'kit_id' => $kit->id,
					'insumo_id' => $item['insumo_id'],
					'cantidad' => $item['cantidad'],
					'unidad_medida_id' => $item['unidad_medida_id'],
				]);
			}
		});

		return redirect()->route('kits.index')->with('success', 'Kit creado correctamente.');
	}

	public function edit($id)
	{
		$kit     = Kit::with('detalles.insumo')->findOrFail($id);
		$insumos = Insumo::where('estado', 1)->orderBy('nombre')->get();
		return view('kits.edit', compact('kit', 'insumos'));
	}

	public function update(Request $request, $id)
	{
		$request->validate([
			'descripcion' => 'required|string|max:255',
			'detalle'     => 'required|json',
		]);

		$detalle = json_decode($request->detalle, true);

		if (empty($detalle)) {
			return back()->withErrors(['detalle' => 'Debe agregar al menos un insumo al kit.'])->withInput();
		}

		$kit = Kit::findOrFail($id);

		DB::transaction(function () use ($kit, $request, $detalle) {
			$kit->update(['descripcion' => $request->descripcion]);

			$kit->detalles()->delete();

			foreach ($detalle as $item) {
				KitDetalle::create([
					'kit_id'           => $kit->id,
					'insumo_id'        => $item['insumo_id'],
					'cantidad'         => $item['cantidad'],
					'unidad_medida_id' => $item['unidad_medida_id'],
				]);
			}
		});

		return redirect()->route('kits.index')->with('success', 'Kit actualizado correctamente.');
	}
}
