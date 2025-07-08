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
        Schema::create('presupuesto_aprobados', function (Blueprint $table) {
            $table->id();
            $table->date('fecha_carga');
            $table->date('fecha_aprobacion')->nullable();
            $table->date('fecha_gestion')->nullable();
            $table->unsignedBigInteger('usuario_id');
            $table->foreign('usuario_id')->references('id')->on('usuarios');
            $table->unsignedBigInteger('validado_por')->nullable();
            $table->foreign('validado_por')->references('id')->on('usuarios');
            $table->unsignedBigInteger('gestionado_por')->nullable();
            $table->foreign('gestionado_por')->references('id')->on('usuarios');
            $table->unsignedBigInteger('obra_id')->nullable();
            $table->foreign('obra_id')->references('id')->on('obras');
            $table->string('presupuesto');
            $table->string('ubicacion');
            $table->string('observacion')->nullable();
            $table->integer('monto_total');
            $table->integer('estado');
            $table->integer('tipo_trabajo');
            $table->integer('anticipo')->nullable();
            $table->string('orden_trabajo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presupuesto_aprobados');
    }
};
