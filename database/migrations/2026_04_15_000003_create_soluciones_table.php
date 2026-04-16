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
        Schema::create('soluciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('problema_id')->constrained('problemas');
            $table->text('descripcion');
            $table->timestamp('stamp')->nullable();
            $table->decimal('avance', 5, 2)->default(0);
            $table->integer('estado')->default(0);
            $table->integer('orden')->default(0);
            $table->foreignId('usuario_id')->constrained('usuarios');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('soluciones');
    }
};
