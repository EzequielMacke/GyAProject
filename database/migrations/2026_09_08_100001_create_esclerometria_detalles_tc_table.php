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
        Schema::create('esclerometria_detalles_tc', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('esclerometria_tc_id');
            $table->foreign('esclerometria_tc_id')->references('id')->on('esclerometrias_tc')->onDelete('cascade');
            $table->string('elemento')->nullable();
            $table->integer('direccion')->default(0);
            $table->json('impactos')->nullable();
            $table->decimal('promedio_inicial', 8, 2)->nullable();
            $table->integer('validos')->nullable();
            $table->decimal('promedio_final', 8, 2)->nullable();
            $table->decimal('n_corregido', 8, 2)->nullable();
            $table->decimal('correccion_angulo', 8, 2)->nullable();
            $table->decimal('n_final', 8, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('esclerometria_detalles_tc');
    }
};
