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
        Schema::create('recibo_ventas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nro_recibo')->nullable();
            $table->date('fecha_emision')->nullable();
            $table->string('concepto')->nullable();
            $table->decimal('monto', 10, 2)->nullable();
            $table->unsignedInteger('factura_id')->nullable();
            $table->foreign('factura_id')->references('id')->on('factura_ventas');
            $table->unsignedBigInteger('presupuesto_aprobado_id')->nullable();
            $table->foreign('presupuesto_aprobado_id')->references('id')->on('presupuesto_aprobados');
            $table->unsignedBigInteger('usuario_id')->nullable();
            $table->foreign('usuario_id')->references('id')->on('usuarios');
            $table->unsignedBigInteger('obra_id')->nullable();
            $table->foreign('obra_id')->references('id')->on('obras');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recibo_ventas');
    }
};
