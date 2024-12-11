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
        Schema::create('aquisicao_bem', function (Blueprint $table) {
            $table->id();
            $table->date('data_aquisicao');
            $table->string('status');
            $table->string('nome');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->decimal('valor', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aquisicao_bem');
    }
};
