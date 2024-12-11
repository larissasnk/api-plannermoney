<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GastoFamiliar extends Model
{
    protected $table = 'gastos_familiares';

    protected $fillable = [
        'transacao_id',
        'tipo_membro',
        'nome_membro',
        'gasto_previsto',
        'gasto_realizado',
        'user_id'
    ];

    public function transacao()
    {
        return $this->belongsTo(Transacao::class);
    }
}

