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
        Schema::create('presupuestos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre');
            $table->date('fecha');
            $table->unsignedBigInteger('usuario_id');
            $table->foreign('usuario_id')->references('id')->on('usuarios');
            $table->unsignedBigInteger('obra_id')->nullable();
            $table->foreign('obra_id')->references('id')->on('obras');
            $table->string('presupuesto')->nullable();
            $table->string('conformidad')->nullable();
            $table->string('orden_trabajo')->nullable();
            $table->decimal('monto', 20, 2)->nullable();
            $table->decimal('cotizacion', 20, 2)->nullable();
            $table->unsignedInteger('moneda_id')->nullable();
            $table->foreign('moneda_id')->references('id')->on('monedas');
            $table->unsignedInteger('tipo_trabajo_id')->nullable();
            $table->foreign('tipo_trabajo_id')->references('id')->on('tipo_trabajos');
            $table->unsignedInteger('estado_id')->nullable();
            $table->foreign('estado_id')->references('id')->on('estados');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presupuestos');
    }
};
