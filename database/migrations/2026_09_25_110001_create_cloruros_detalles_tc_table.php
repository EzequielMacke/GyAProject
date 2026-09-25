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
        Schema::create('cloruros_detalles_tc', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cloruros_tc_id');
            $table->foreign('cloruros_tc_id', 'clo_tc_id_foreign')
                ->references('id')->on('cloruros_tc')->onDelete('cascade');
            $table->string('elemento')->nullable();
            $table->unsignedBigInteger('nivel_pla_tc_id')->nullable();
            $table->foreign('nivel_pla_tc_id', 'clo_det_nivel_foreign')
                ->references('id')->on('nivel_pla_tc')->nullOnDelete();
            $table->decimal('recubrimiento', 8, 2)->nullable();
            $table->json('espesores')->nullable();
            $table->decimal('espesor_cloruros', 8, 2)->nullable();
            $table->decimal('porcentaje_afectado', 8, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cloruros_detalles_tc');
    }
};
