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
        Schema::create('kit_detalles', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('kit_id');
            $table->foreign('kit_id')->references('id')->on('kits');
            $table->unsignedBigInteger('insumo_id');
            $table->foreign('insumo_id')->references('id')->on('insumos');
            $table->decimal('cantidad', 8, 2);
            $table->integer('unidad_medida_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kit_detalles');
    }
};
