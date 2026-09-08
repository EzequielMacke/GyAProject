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
        Schema::create('ultrasonido_indirecto_detalles_tc', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ultrasonido_indirecto_tc_id');
            $table->foreign('ultrasonido_indirecto_tc_id', 'uid_tc_id_foreign')
                ->references('id')->on('ultrasonidos_indirectos_tc')->onDelete('cascade');
            $table->string('elemento')->nullable();
            $table->json('velocidades')->nullable();
            $table->decimal('promedio', 10, 2)->nullable();
            $table->decimal('desviacion_estandar', 10, 3)->nullable();
            $table->decimal('coeficiente_variacion', 8, 2)->nullable();
            $table->boolean('repetir_ensayo')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ultrasonido_indirecto_detalles_tc');
    }
};
