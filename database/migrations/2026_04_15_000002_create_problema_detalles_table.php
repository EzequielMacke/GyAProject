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
        Schema::create('problema_detalles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('problema_id')->constrained('problemas');
            $table->string('foto')->nullable();
            $table->foreignId('usuario_id')->constrained('usuarios');
            $table->integer('estado')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('problema_detalles');
    }
};
