<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Informes del generador. Título y referencia van en columnas propias
     * para listarlos y buscarlos; el resto del formulario va en "datos"
     * (JSON), así agregar una parte nueva al informe no requiere migración.
     */
    public function up(): void
    {
        Schema::create('informes_tc', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('obra_tc_id');
            $table->foreign('obra_tc_id')->references('id')->on('obras_tc')->onDelete('cascade');
            $table->unsignedBigInteger('usuario_id')->nullable();
            $table->foreign('usuario_id')->references('id')->on('usuarios');
            $table->string('titulo')->nullable();
            $table->string('referencia')->nullable();
            $table->json('datos');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('informes_tc');
    }
};
