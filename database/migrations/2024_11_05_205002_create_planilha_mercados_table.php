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
        Schema::create('planilha_mercado', function (Blueprint $table) {
            $table->id();
            $table->string('nome_item');
            $table->decimal('valor', 10, 2);
            $table->string('tipo_unidade');
            $table->integer('quantidade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('planilha_mercado');
    }
};
