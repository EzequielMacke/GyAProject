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
        Schema::create('contactos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('obra_id');
            $table->foreign('obra_id')->references('id')->on('obras');
            $table->unsignedBigInteger('presupuesto_id')->nullable();
            $table->foreign('presupuesto_id')->references('id')->on('presupuesto_aprobados');
            $table->string('nombre');
            $table->string('telefono')->nullable();
            $table->string('email')->nullable();
            $table->text('observacion')->nullable();
            $table->string('tipo_contacto')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contactos');
    }
};
