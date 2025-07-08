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
        Schema::create('documento_detalles', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('documento_id');
            $table->foreign('documento_id')->references('id')->on('documentos');
            $table->unsignedInteger('tipo_ensayo_id');
            $table->foreign('tipo_ensayo_id')->references('id')->on('tipo_ensayos');
            $table->string('ruta')->nullable();
            $table->string('pie')->nullable();
            $table->integer('identificador');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documento_detalles');
    }
};
