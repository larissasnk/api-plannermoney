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
        Schema::create('metodo_50_20_30', function (Blueprint $table) {
            $table->id();
            $table->string('tipo');
            $table->string('nome');
            $table->decimal('valor_previsto', 10, 2);
            $table->decimal('valor_real', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('metodo502030s');
    }
};
