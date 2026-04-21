<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bibliografia_detalles', function (Blueprint $table) {
            $table->decimal('tamanio', 5, 2)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('bibliografia_detalles', function (Blueprint $table) {
            $table->integer('tamanio')->nullable()->change();
        });
    }
};
