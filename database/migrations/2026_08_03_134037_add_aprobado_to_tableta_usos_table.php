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
            $table->boolean('aprobado')->default(0)->after('usuario_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tableta_usos', function (Blueprint $table) {
            $table->dropColumn('aprobado');
        });
    }
};
