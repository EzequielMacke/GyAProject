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
        Schema::create('tabletas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('clave')->nullable();
            $table->string('nombre')->nullable();
            $table->string('modelo')->nullable();
            $table->string('serie')->nullable();
            $table->string('sim')->nullable();
            $table->string('estado')->nullable();
            $table->string('codigo_qr')->nullable();
            $table->string('observacion')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tabletas');
    }
};
