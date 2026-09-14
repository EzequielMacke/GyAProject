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
        Schema::create('medicion_fisura_detalles_tc', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('medicion_fisura_tc_id');
            $table->foreign('medicion_fisura_tc_id', 'med_fis_tc_id_foreign')
                ->references('id')->on('medicion_fisuras_tc')->onDelete('cascade');
            $table->string('elemento')->nullable();
            $table->unsignedBigInteger('nivel_pla_tc_id')->nullable();
            $table->foreign('nivel_pla_tc_id', 'med_fis_det_nivel_foreign')
                ->references('id')->on('nivel_pla_tc')->nullOnDelete();
            $table->decimal('ancho', 8, 2)->nullable();
            $table->json('espesores')->nullable();
            $table->json('profundidades')->nullable();
            $table->boolean('pasante')->default(false);
            $table->decimal('promedio_espesor', 8, 2)->nullable();
            $table->decimal('promedio_profundidad', 8, 2)->nullable();
            $table->decimal('porcentaje_afectado', 8, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicion_fisura_detalles_tc');
    }
};
