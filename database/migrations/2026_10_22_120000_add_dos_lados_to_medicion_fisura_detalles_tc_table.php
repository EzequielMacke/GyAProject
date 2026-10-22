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
        Schema::table('medicion_fisura_detalles_tc', function (Blueprint $table) {
            // Fisura medida también del otro lado del elemento (lado B).
            $table->boolean('dos_lados')->default(false)->after('profundidades');
            $table->json('espesores_b')->nullable()->after('dos_lados');
            $table->json('profundidades_b')->nullable()->after('espesores_b');
            $table->decimal('promedio_espesor_b', 8, 2)->nullable()->after('promedio_profundidad');
            $table->decimal('promedio_profundidad_b', 8, 2)->nullable()->after('promedio_espesor_b');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('medicion_fisura_detalles_tc', function (Blueprint $table) {
            $table->dropColumn(['dos_lados', 'espesores_b', 'profundidades_b', 'promedio_espesor_b', 'promedio_profundidad_b']);
        });
    }
};
