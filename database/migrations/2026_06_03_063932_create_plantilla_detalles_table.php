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
        Schema::create('plantilla_detalles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('plantilla_id');
            $table->string('ruta');
            $table->timestamps();

            $table->foreign('plantilla_id')->references('id')->on('plantillas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plantilla_detalles');
    }
};
