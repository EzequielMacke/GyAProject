<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModuloSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $modulos = [
            ['nombre' => 'obr',     'descripcion' => 'Obra',                     'estado' => 1],
            ['nombre' => 'dir',     'descripcion' => 'Directorio',               'estado' => 1],
            ['nombre' => 'pre_apr', 'descripcion' => 'Presupuesto Aprobado',     'estado' => 1],
            ['nombre' => 'ped_ins', 'descripcion' => 'Pedidos de Insumos',       'estado' => 1],
            ['nombre' => 'con',     'descripcion' => 'Contactos',                'estado' => 1],
            ['nombre' => 'inv',     'descripcion' => 'Inventario',               'estado' => 1],
            ['nombre' => 'fac',     'descripcion' => 'Facturacion',              'estado' => 1],
            ['nombre' => 'dat',     'descripcion' => 'Datos de Obra',            'estado' => 1],
            ['nombre' => 'tab',     'descripcion' => 'Tables',                   'estado' => 1],
            ['nombre' => 'equ',     'descripcion' => 'Equipos',                  'estado' => 1],
            ['nombre' => 'veh',     'descripcion' => 'Vehiculos',                'estado' => 1],
            ['nombre' => 'per',     'descripcion' => 'Permisos',                 'estado' => 1],
            ['nombre' => 'usu',     'descripcion' => 'Usuarios',                 'estado' => 1],
            ['nombre' => 'her',     'descripcion' => 'Herramientas',             'estado' => 1],
            ['nombre' => 'man',     'descripcion' => 'Mantenimiento',            'estado' => 1],
            ['nombre' => 'rep',     'descripcion' => 'Reportes',                 'estado' => 1],
            ['nombre' => 'ins',     'descripcion' => 'Insumos',                  'estado' => 1],
            ['nombre' => 'kit',     'descripcion' => 'Kits',                     'estado' => 1],
            ['nombre' => 'pre_ped', 'descripcion' => 'Preparar Pedidos',         'estado' => 1],
            ['nombre' => 'asi_ord', 'descripcion' => 'Asignacion de OT',         'estado' => 1],
            ['nombre' => 'car_fac', 'descripcion' => 'Facturacion',              'estado' => 1],
            ['nombre' => 'sit_ava', 'descripcion' => 'Situacion de Avances',     'estado' => 1],
            ['nombre' => 'pro',     'descripcion' => 'Programa de Mejoramiento', 'estado' => 1],
            ['nombre' => 'sol',     'descripcion' => 'Soluciones',               'estado' => 1],
            ['nombre' => 'bib',     'descripcion' => 'Bibliografía',             'estado' => 1],
            ['nombre' => 'pla',     'descripcion' => 'Plantilla',                'estado' => 1],
            ['nombre' => 'con_gas', 'descripcion' => 'Control de Gastos',        'estado' => 1],
        ];

        foreach ($modulos as $modulo) {
            if (! DB::table('modulos')->where('nombre', $modulo['nombre'])->exists()) {
                DB::table('modulos')->insert($modulo);
            }
        }
    }
}
