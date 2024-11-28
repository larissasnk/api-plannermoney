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
        Schema::create('objetivo_financeiro', function (Blueprint $table) {
            $table->id();
            $table->string('tipo_objetivo');
            $table->date('data');
            $table->decimal('valor', 10, 2);
            $table->text('plano');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('objetivo_financeiro');
    }
};
