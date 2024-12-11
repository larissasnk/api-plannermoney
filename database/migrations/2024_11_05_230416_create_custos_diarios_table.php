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
        Schema::create('custos_diarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('planejamento_viagem_id')->constrained('planejamento_viagem');
            $table->date('data_diaria');
            $table->decimal('alimentacao_valor', 10, 2);
            $table->decimal('passeio_valor', 10, 2);
            $table->decimal('transporte_valor', 10, 2);
            $table->decimal('extra_valor', 10, 2);
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custo_diarios');
    }
};
