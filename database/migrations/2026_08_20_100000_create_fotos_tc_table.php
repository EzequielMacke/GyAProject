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
        Schema::create('fotos_tc', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('obra_tc_id');
            $table->foreign('obra_tc_id')->references('id')->on('obras_tc')->onDelete('cascade');
            $table->unsignedBigInteger('plano_tc_id')->nullable();
            $table->foreign('plano_tc_id')->references('id')->on('planos')->onDelete('cascade');
            $table->string('clasificacion')->nullable();
            $table->string('archivo');
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
        Schema::dropIfExists('fotos_tc');
    }
};
