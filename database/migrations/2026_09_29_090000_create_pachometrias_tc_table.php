<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Una fila por pachometría (cada tarjeta de la pantalla). Los
     * parámetros del dibujo (medidas, recubrimiento, estribos, listas de
     * barras, mallas de la losa, referencias, etc.) cambian según el tipo
     * de elemento, por eso van juntos en "datos" (JSON).
     */
    public function up(): void
    {
        Schema::create('pachometrias_tc', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('obra_tc_id');
            $table->foreign('obra_tc_id')->references('id')->on('obras_tc')->onDelete('cascade');
            $table->unsignedBigInteger('usuario_id')->nullable();
            $table->foreign('usuario_id')->references('id')->on('usuarios');
            $table->unsignedInteger('orden')->nullable();
            $table->unsignedInteger('numero')->nullable();
            $table->string('tipo', 20)->nullable();
            $table->string('elemento', 60)->nullable();
            $table->json('datos')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pachometrias_tc');
    }
};
