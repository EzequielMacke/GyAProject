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
        Schema::create('nivel_pla_tc', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('obra_tc_id');
            $table->foreign('obra_tc_id')->references('id')->on('obras_tc')->onDelete('cascade');
            $table->string('descripcion');
            $table->timestamps();

            $table->unique(['obra_tc_id', 'descripcion']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nivel_pla_tc');
    }
};
