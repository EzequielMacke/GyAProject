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
        Schema::create('situacion_avances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('presupuesto_aprobado_id')->constrained('presupuesto_aprobados');
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->foreignId('estado_situacion_id')->constrained('estado_situacions');
            $table->integer('plazo')->nullable();
            $table->text('observacion')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('situacion_avances');
    }
};
