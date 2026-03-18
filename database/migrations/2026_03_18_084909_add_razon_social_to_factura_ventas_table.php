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
        Schema::table('factura_ventas', function (Blueprint $table) {
            $table->string('razon_social')->nullable()->after('concepto');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('factura_ventas', function (Blueprint $table) {
            $table->dropColumn('razon_social');
        });
    }
};
