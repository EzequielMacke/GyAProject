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
        Schema::create('esclerometrias_tc', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('obra_tc_id');
            $table->foreign('obra_tc_id')->references('id')->on('obras_tc')->onDelete('cascade');
            $table->unsignedBigInteger('usuario_id')->nullable();
            $table->foreign('usuario_id')->references('id')->on('usuarios');
            $table->date('fecha');
            $table->decimal('lectura_inicial_yunque', 8, 2)->nullable();
            $table->decimal('lectura_final_yunque', 8, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('esclerometrias_tc');
    }
};
