<?php

namespace App\Console\Commands;

use App\Models\FotoTc;
use App\Services\MiniaturaFotoService;
use Illuminate\Console\Command;

class GenerarMiniaturasFotos extends Command
{
    protected $signature = 'galeria:miniaturas';

    protected $description = 'Genera las miniaturas que falten de las fotos de trabajo de campo (galería)';

    public function handle()
    {
        $generadas = 0;
        $fallidas = 0;
        $total = FotoTc::count();

        $barra = $this->output->createProgressBar($total);

        FotoTc::select('id', 'archivo')->chunkById(200, function ($fotos) use (&$generadas, &$fallidas, $barra) {
            foreach ($fotos as $foto) {
                if (! MiniaturaFotoService::existe($foto->archivo)) {
                    MiniaturaFotoService::generar($foto->archivo) ? $generadas++ : $fallidas++;
                }
                $barra->advance();
            }
        });

        $barra->finish();
        $this->newLine();
        $this->info("Miniaturas generadas: {$generadas}. Sin original o ilegibles: {$fallidas}.");

        return 0;
    }
}
