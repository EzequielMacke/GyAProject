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
        Schema::create('resistividad_detalles_tc', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('resistividad_tc_id');
            $table->foreign('resistividad_tc_id', 'res_tc_id_foreign')
                ->references('id')->on('resistividades_tc')->onDelete('cascade');
            $table->string('elemento')->nullable();
            $table->unsignedBigInteger('nivel_pla_tc_id')->nullable();
            $table->foreign('nivel_pla_tc_id', 'res_det_nivel_foreign')
                ->references('id')->on('nivel_pla_tc')->nullOnDelete();
            $table->json('lecturas')->nullable();
            $table->decimal('temperatura', 5, 2)->nullable();
            $table->decimal('promedio', 10, 2)->nullable();
            $table->decimal('correccion', 10, 2)->nullable();
            $table->decimal('resistividad_final', 10, 2)->nullable();
            $table->string('velocidad_corrosion')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resistividad_detalles_tc');
    }
};
