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
        Schema::table('carbonatacion_detalles_tc', function (Blueprint $table) {
            $table->unsignedBigInteger('nivel_pla_tc_id')->nullable()->after('elemento');
            $table->foreign('nivel_pla_tc_id', 'carb_det_nivel_foreign')
                ->references('id')->on('nivel_pla_tc')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('carbonatacion_detalles_tc', function (Blueprint $table) {
            $table->dropForeign('carb_det_nivel_foreign');
            $table->dropColumn('nivel_pla_tc_id');
        });
    }
};
