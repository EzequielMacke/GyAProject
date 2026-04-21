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
        Schema::create('bibliografia_detalles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bibliografia_id');
            $table->foreign('bibliografia_id')->references('id')->on('bibliografias');
            $table->unsignedBigInteger('elemento_plantilla_id');
            $table->foreign('elemento_plantilla_id')->references('id')->on('elemento_plantillas');
            $table->text('descripcion');
            $table->integer('tamanio')->nullable();
            $table->integer('estado')->default(1);
            $table->integer('orden')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bibliografia_detalles');
    }
};
