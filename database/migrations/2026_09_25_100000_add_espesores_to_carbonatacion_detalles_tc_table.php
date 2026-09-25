<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Según RILEM CPC-18 el espesor carbonatado se mide 4 veces por
     * punto: las mediciones se guardan en "espesores" y la columna
     * "espesor_carbonatado" pasa a guardar el promedio de ellas.
     */
    public function up(): void
    {
        Schema::table('carbonatacion_detalles_tc', function (Blueprint $table) {
            $table->json('espesores')->nullable()->after('recubrimiento');
        });

        // Los puntos cargados antes tenían una sola medición: queda como la primera.
        DB::table('carbonatacion_detalles_tc')
            ->whereNotNull('espesor_carbonatado')
            ->orderBy('id')
            ->each(function ($detalle) {
                DB::table('carbonatacion_detalles_tc')
                    ->where('id', $detalle->id)
                    ->update(['espesores' => json_encode([(float) $detalle->espesor_carbonatado, null, null, null])]);
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('carbonatacion_detalles_tc', function (Blueprint $table) {
            $table->dropColumn('espesores');
        });
    }
};
