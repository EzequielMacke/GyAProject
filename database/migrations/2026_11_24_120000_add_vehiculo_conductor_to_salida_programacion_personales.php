<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Opcional: en qué vehículo de la salida va cada persona, y si lo conduce.
     *   - salida_vehiculo_id null → la persona va en la salida, sin vehículo especificado.
     *   - conductor               → solo puede haber uno por vehículo (se controla al guardar).
     */
    public function up(): void
    {
        Schema::table('salida_programacion_personales', function (Blueprint $table) {
            $table->unsignedBigInteger('salida_vehiculo_id')->nullable()->after('salida_personal_id');
            $table->foreign('salida_vehiculo_id', 'sal_prog_per_veh_fk')
                ->references('id')->on('salida_vehiculos')->nullOnDelete();
            $table->boolean('conductor')->default(false)->after('salida_vehiculo_id');
        });
    }

    public function down(): void
    {
        Schema::table('salida_programacion_personales', function (Blueprint $table) {
            $table->dropForeign('sal_prog_per_veh_fk');
            $table->dropColumn(['salida_vehiculo_id', 'conductor']);
        });
    }
};
