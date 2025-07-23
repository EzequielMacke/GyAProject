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
        Schema::create('pedido_para_obra_detalles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pedido_para_obra_id');
            $table->foreign('pedido_para_obra_id')->references('id')->on('pedido_para_obras');
            $table->unsignedBigInteger('insumo_id');
            $table->foreign('insumo_id')->references('id')->on('insumos');
            $table->integer('cantidad');
            $table->integer('medida');
            $table->integer('confirmado');
            $table->unsignedBigInteger('usuario_id')->nullable();
            $table->foreign('usuario_id')->references('id')->on('usuarios');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedido_para_obra_detalles');
    }
};
