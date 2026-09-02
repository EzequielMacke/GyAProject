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
        Schema::create('etiqueta_detalles_tc', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('foto_tc_id');
            $table->foreign('foto_tc_id')->references('id')->on('fotos_tc')->onDelete('cascade');
            $table->unsignedBigInteger('etiqueta_tc_id');
            $table->foreign('etiqueta_tc_id')->references('id')->on('etiquetas_tc')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('etiqueta_detalles_tc');
    }
};
