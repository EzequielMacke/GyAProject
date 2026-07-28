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
        Schema::create('control_gastos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('obra_id')->nullable();
            $table->foreign('obra_id')->references('id')->on('obras');
            $table->unsignedBigInteger('presupuesto_aprobado_id')->nullable();
            $table->foreign('presupuesto_aprobado_id')->references('id')->on('presupuesto_aprobados');
            $table->decimal('ingenieros', 15, 2)->nullable();
            $table->decimal('tecnicos', 15, 2)->nullable();
            $table->decimal('mano_obra', 15, 2)->nullable();
            $table->decimal('otros', 15, 2)->nullable();
            $table->string('observacion')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('control_gastos');
    }
};
