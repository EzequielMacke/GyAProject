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
        Schema::create('facturas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('numero');
            $table->string('adjunto')->nullable();
            $table->unsignedInteger('presupuesto_id');
            $table->foreign('presupuesto_id')->references('id')->on('presupuestos');
            $table->string('concepto');
            $table->decimal('monto', 20, 2);
            $table->decimal('cotizacion', 20, 2);
            $table->unsignedInteger('moneda_id');
            $table->foreign('moneda_id')->references('id')->on('monedas');
            $table->date('fecha');
            $table->unsignedBigInteger('usuario_id');
            $table->foreign('usuario_id')->references('id')->on('usuarios');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facturas');
    }
};
