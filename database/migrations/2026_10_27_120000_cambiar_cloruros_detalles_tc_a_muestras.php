<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('cloruros_detalles_tc', function (Blueprint $table) {
            // Cada punto se ensaya con 3 perforaciones: de cada una se registra
            // la profundidad (mm), el peso del polvo recogido (g) y el
            // contenido de cloruros informado por el laboratorio (% Cl).
            $table->json('muestras')->nullable()->after('nivel_pla_tc_id');
            $table->dropColumn(['recubrimiento', 'espesores', 'espesor_cloruros', 'porcentaje_afectado']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cloruros_detalles_tc', function (Blueprint $table) {
            $table->dropColumn('muestras');
            $table->decimal('recubrimiento', 8, 2)->nullable();
            $table->json('espesores')->nullable();
            $table->decimal('espesor_cloruros', 8, 2)->nullable();
            $table->decimal('porcentaje_afectado', 8, 2)->nullable();
        });
    }
};
