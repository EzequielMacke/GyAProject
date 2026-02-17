<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('obras', function (Blueprint $table) {
            $table->dropColumn([
                'contacto',
                'numero',
                'peticionario',
                'ruc',
                'razon_social',
                'direccion_fac',
                'correo_fac',
                'correo_pet',
                'nombre_obr',
                'telefono_obr',
                'correo_obr',
                'nombre_adm',
                'telefono_adm',
                'correo_adm'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('obras', function (Blueprint $table) {
            $table->string('contacto')->nullable();
            $table->string('numero')->nullable();
            $table->string('peticionario')->nullable();
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
};
