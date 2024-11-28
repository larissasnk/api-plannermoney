<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustoDiario extends Model
{
    protected $table = 'custos_diarios';

    protected $fillable = [
        'planejamento_viagem_id',
        'alimentacao_valor',
        'passeio_valor',
        'transporte_valor',
        'extra_valor',
        'data_diaria',
    ];

    public function planejamentoViagem()
    {
        return $this->belongsTo(PlanejamentoViagem::class, 'planejamento_viagem_id');
    }
}
