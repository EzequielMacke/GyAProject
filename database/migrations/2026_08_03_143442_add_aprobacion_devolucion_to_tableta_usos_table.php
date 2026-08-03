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
        Schema::table('tableta_usos', function (Blueprint $table) {
            $table->boolean('aprobacion_devolucion')->default(0)->after('fecha_devolucion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tableta_usos', function (Blueprint $table) {
            $table->dropColumn('aprobacion_devolucion');
        });
    }
};
