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
        DB::table('modulos')->insert([
            ['id' => 1,  'nombre' => 'are_ing',      'descripcion' => 'Area de Ingeniería',                          'estado' => 1],
            ['id' => 2,  'nombre' => 'are_dep',      'descripcion' => 'Area de Deposito',                            'estado' => 1],
            ['id' => 3,  'nombre' => 'ped_obr_ing',  'descripcion' => 'Pedido para obra -Ingenieria',                'estado' => 1],
            ['id' => 4,  'nombre' => 'ped_obr_dep',  'descripcion' => 'Pedido para obra -Deposito',                  'estado' => 1],
            ['id' => 5,  'nombre' => 'man',          'descripcion' => 'Mantenimiento',                               'estado' => 1],
            ['id' => 6,  'nombre' => 'ins',          'descripcion' => 'Insumos',                                     'estado' => 1],
            ['id' => 7,  'nombre' => 'obr',          'descripcion' => 'Obras',                                       'estado' => 1],
            ['id' => 8,  'nombre' => 'usu',          'descripcion' => 'Usuarios',                                    'estado' => 1],
            ['id' => 9,  'nombre' => 'per',          'descripcion' => 'Permisos',                                    'estado' => 1],
            ['id' => 10, 'nombre' => 'pre_apr_ing',  'descripcion' => 'Presupuestos Aprobados - Ingenieria',         'estado' => 1],
            ['id' => 11, 'nombre' => 'are_adm',      'descripcion' => 'Area de Adminitracion',                       'estado' => 1],
            ['id' => 12, 'nombre' => 'pre_apr_adm',  'descripcion' => 'Presupuestos Aprobados - Administracion',     'estado' => 1],
            ['id' => 13, 'nombre' => 'val_pre_apr',  'descripcion' => 'Validacion de Presupuestos Aprobados',        'estado' => 1],
            ['id' => 14, 'nombre' => 'age_tra',      'descripcion' => 'Agendamiento de trabajos',                    'estado' => 1],
            ['id' => 15, 'nombre' => 'ges_tra',      'descripcion' => 'Gestion de Trabajos',                         'estado' => 1],
            ['id' => 16, 'nombre' => 'ges_tra_asig', 'descripcion' => 'Asignaciones - Gestion de Trabajos',          'estado' => 1],
            ['id' => 17, 'nombre' => 'her',          'descripcion' => 'Herramientas',                                'estado' => 1],
            ['id' => 18, 'nombre' => 'gen_doc',      'descripcion' => 'Generador de Documentos',                     'estado' => 1],
        ]);
    }
}
