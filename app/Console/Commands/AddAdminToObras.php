<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Obra;
use App\Models\Directorio;

class AddAdminToObras extends Command
{
    protected $signature = 'admin:add-to-obras';

    protected $description = 'Agrega al admin (usuario 1) al directorio de todas las obras que no lo tienen';

    public function handle()
    {
        $obras = Obra::whereDoesntHave('directorios', function ($query) {
            $query->where('usuario_id', 1);
        })->get();

        if ($obras->isEmpty()) {
            $this->info('El admin ya está en todas las obras.');
            return 0;
        }

        foreach ($obras as $obra) {
            Directorio::create([
                'obra_id'    => $obra->id,
                'usuario_id' => 1,
                'fecha'      => now(),
            ]);
            $this->info("Admin agregado a obra: {$obra->nombre} (ID: {$obra->id})");
        }

        $this->info('Proceso finalizado correctamente.');
        return 0;
    }
}
