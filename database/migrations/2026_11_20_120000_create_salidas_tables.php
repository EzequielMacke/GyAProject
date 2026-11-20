<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Salidas.
     *
     * Catálogos (se reutilizan en cualquier fecha, no se cargan por día):
     *   - salidas             → nombres de salidas
     *   - salida_vehiculos    → vehículos
     *   - salida_personales   → personal
     *   Ninguno se elimina: se "ocultan" con la columna `oculto`.
     *
     * Programación por día (lo que está en el tablero de una fecha):
     *   - salida_programaciones          → qué salidas hay en una fecha
     *   - salida_programacion_vehiculos  → vehículos asignados a cada salida de esa fecha
     *   - salida_programacion_personales → personal asignado a cada salida de esa fecha
     *   Las tablas de asignación repiten la `fecha` para garantizar por base de datos
     *   que un vehículo / una persona no esté en dos salidas el mismo día.
     */
    public function up(): void
    {
        // ── Catálogos ──
        Schema::create('salidas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 150);
            $table->boolean('oculto')->default(false);
            $table->unsignedBigInteger('usuario_id')->nullable();
            $table->foreign('usuario_id')->references('id')->on('usuarios')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('salida_vehiculos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 150);
            $table->boolean('oculto')->default(false);
            $table->unsignedBigInteger('usuario_id')->nullable();
            $table->foreign('usuario_id')->references('id')->on('usuarios')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('salida_personales', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 150);
            $table->boolean('oculto')->default(false);
            $table->unsignedBigInteger('usuario_id')->nullable();
            $table->foreign('usuario_id')->references('id')->on('usuarios')->nullOnDelete();
            $table->timestamps();
        });

        // ── Programación por día ──
        Schema::create('salida_programaciones', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->unsignedBigInteger('salida_id');
            $table->foreign('salida_id')->references('id')->on('salidas')->cascadeOnDelete();
            $table->unsignedBigInteger('usuario_id')->nullable();
            $table->foreign('usuario_id')->references('id')->on('usuarios')->nullOnDelete();
            $table->timestamps();

            $table->unique(['fecha', 'salida_id']);
            $table->index('fecha');
        });

        Schema::create('salida_programacion_vehiculos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('salida_programacion_id');
            $table->foreign('salida_programacion_id', 'sal_prog_veh_prog_fk')
                ->references('id')->on('salida_programaciones')->cascadeOnDelete();
            $table->unsignedBigInteger('salida_vehiculo_id');
            $table->foreign('salida_vehiculo_id', 'sal_prog_veh_veh_fk')
                ->references('id')->on('salida_vehiculos')->cascadeOnDelete();
            $table->date('fecha');

            $table->unique(['fecha', 'salida_vehiculo_id'], 'sal_prog_veh_fecha_unique');
        });

        Schema::create('salida_programacion_personales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('salida_programacion_id');
            $table->foreign('salida_programacion_id', 'sal_prog_per_prog_fk')
                ->references('id')->on('salida_programaciones')->cascadeOnDelete();
            $table->unsignedBigInteger('salida_personal_id');
            $table->foreign('salida_personal_id', 'sal_prog_per_per_fk')
                ->references('id')->on('salida_personales')->cascadeOnDelete();
            $table->date('fecha');

            $table->unique(['fecha', 'salida_personal_id'], 'sal_prog_per_fecha_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salida_programacion_personales');
        Schema::dropIfExists('salida_programacion_vehiculos');
        Schema::dropIfExists('salida_programaciones');
        Schema::dropIfExists('salida_personales');
        Schema::dropIfExists('salida_vehiculos');
        Schema::dropIfExists('salidas');
    }
};
