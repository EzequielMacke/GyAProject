<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Identificador que genera el propio dispositivo al agregar la tarjeta,
     * para poder crear y guardar pachometrías sin conexión: el guardado
     * crea o actualiza según este uid, así que no hace falta esperar a que
     * el servidor asigne un id, y reenviar un guardado nunca la duplica.
     */
    public function up(): void
    {
        Schema::table('pachometrias_tc', function (Blueprint $table) {
            $table->uuid('uid')->nullable()->after('id');
        });

        DB::table('pachometrias_tc')->whereNull('uid')->orderBy('id')->each(function ($fila) {
            DB::table('pachometrias_tc')->where('id', $fila->id)->update(['uid' => (string) Str::uuid()]);
        });

        Schema::table('pachometrias_tc', function (Blueprint $table) {
            $table->unique('uid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pachometrias_tc', function (Blueprint $table) {
            $table->dropUnique(['uid']);
            $table->dropColumn('uid');
        });
    }
};
