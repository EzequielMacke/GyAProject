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
        Schema::table('pedido_para_obras', function (Blueprint $table) {
            $table->unsignedBigInteger('presupuesto_aprobado_id')->nullable();
            $table->foreign('presupuesto_aprobado_id')->references('id')->on('presupuesto_aprobados');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pedido_para_obras', function (Blueprint $table) {
            //
        });
    }
};
