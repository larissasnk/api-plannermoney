<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGastoCartaoTable extends Migration
{
    public function up()
    {
        Schema::create('gastos_cartao', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transacao_id')->constrained('transacoes')->onDelete('cascade');
            $table->boolean('parcelado'); // 0 para à vista, 1 para parcelado
            $table->integer('quantidade_parcela')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('gastos_cartao');
    }
}