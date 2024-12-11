<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanejamentoViagem extends Model
{
    protected $table = 'planejamento_viagem';

    protected $fillable = [
        'nome_viagem',
        'inicio_viagem',
        'termino_viagem',
        'valor_hospedagem',
        'valor_total_viagem',
        'user_id'
    ];

    public function custosDiarios()
    {
        return $this->hasMany(CustoDiario::class, 'planejamento_viagem_id');
    }
}

