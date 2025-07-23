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
        Schema::create('pedido_para_obras', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('obra_id');
            $table->foreign('obra_id')->references('id')->on('obras');
            $table->date('fecha_pedido');
            $table->date('fecha_entrega');
            $table->unsignedBigInteger('usuario_id');
            $table->foreign('usuario_id')->references('id')->on('usuarios');
            $table->string('observacion')->nullable();
            $table->integer('total_insumo');
            $table->integer('insumo_confirmado');
            $table->integer('insumo_faltante');
            $table->integer('estado');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedido_para_obras');
    }
};
