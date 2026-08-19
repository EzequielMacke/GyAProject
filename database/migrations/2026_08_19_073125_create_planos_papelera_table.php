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
        Schema::create('planos_papelera', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('plano_id_original');
            $table->string('descripcion')->nullable();
            $table->unsignedBigInteger('obra_id');
            $table->foreign('obra_id')->references('id')->on('obras_tc');
            $table->unsignedBigInteger('grupo_id')->nullable();
            $table->foreign('grupo_id')->references('id')->on('plano_grupo');
            $table->unsignedBigInteger('subgrupo_id')->nullable();
            $table->foreign('subgrupo_id')->references('id')->on('plano_subgrupo');
            $table->string('archivo');
            $table->string('archivo_original')->nullable();
            $table->unsignedBigInteger('usuario_id')->nullable();
            $table->foreign('usuario_id')->references('id')->on('usuarios');
            $table->unsignedSmallInteger('rotacion')->default(0);
            $table->longText('estado')->nullable();
            $table->longText('actividades')->nullable();
            $table->timestamp('creado_originalmente_at')->nullable();
            $table->unsignedBigInteger('eliminado_por');
            $table->foreign('eliminado_por')->references('id')->on('usuarios');
            $table->timestamp('eliminado_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('planos_papelera');
    }
};
