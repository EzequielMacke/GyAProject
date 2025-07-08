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
        Schema::create('documentos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre');
            $table->unsignedInteger('tipo_documento_id');
            $table->foreign('tipo_documento_id')->references('id')->on('tipo_documentos');
            $table->unsignedInteger('tipo_trabajo_id');
            $table->foreign('tipo_trabajo_id')->references('id')->on('tipo_trabajos');
            $table->string('obra');
            $table->string('mes');
            $table->string('año');
            $table->string('peticionario');
            $table->string('referencia');
            $table->date('fecha_presupuesto');
            $table->string('ubicacion');
            $table->string('objeto_alcance');
            $table->unsignedBigInteger('usuario_id');
            $table->foreign('usuario_id')->references('id')->on('usuarios');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documentos');
    }
};
