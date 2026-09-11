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
        Schema::create('carbonatacion_detalles_tc', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('carbonatacion_tc_id');
            $table->foreign('carbonatacion_tc_id', 'carb_tc_id_foreign')
                ->references('id')->on('carbonataciones_tc')->onDelete('cascade');
            $table->string('elemento')->nullable();
            $table->decimal('recubrimiento', 8, 2)->nullable();
            $table->decimal('espesor_carbonatado', 8, 2)->nullable();
            $table->decimal('porcentaje_afectado', 8, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carbonatacion_detalles_tc');
    }
};
