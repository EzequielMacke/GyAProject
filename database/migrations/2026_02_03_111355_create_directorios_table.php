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
        Schema::create('directorios', function (Blueprint $table) {
        $table->increments('id');  
        $table->unsignedBigInteger('obra_id');
        $table->foreign('obra_id')->references('id')->on('obras');
        $table->unsignedBigInteger('usuario_id');
        $table->foreign('usuario_id')->references('id')->on('usuarios');
        $table->date('fecha');
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('directorios');
    }
};
