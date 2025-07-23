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
        Schema::table('obras', function (Blueprint $table) {
            $table->string('ruc')->nullable();
            $table->string('razon_social')->nullable();
            $table->string('direccion_fac')->nullable();
            $table->string('correo_fac')->nullable();
            $table->string('correo_pet')->nullable();
            $table->string('nombre_obr')->nullable();
            $table->string('telefono_obr')->nullable();
            $table->string('correo_obr')->nullable();
            $table->string('nombre_adm')->nullable();
            $table->string('telefono_adm')->nullable();
            $table->string('correo_adm')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('obras', function (Blueprint $table) {
            //
        });
    }
};
