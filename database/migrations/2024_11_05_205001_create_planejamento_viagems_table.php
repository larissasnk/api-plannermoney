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
        Schema::create('planejamento_viagem', function (Blueprint $table) {
            $table->id();
            $table->string('nome_viagem');
            $table->date('inicio_viagem');
            $table->date('termino_viagem');
            $table->decimal('valor_hospedagem', 10, 2);
            $table->decimal('valor_total_viagem', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('planejamento_viagem');
    }
};
