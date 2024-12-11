<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GastoCartao extends Model
{
    protected $table = 'gastos_cartao';

    protected $fillable = [
        'transacao_id',
        'parcelado',
        'quantidade_parcela',
        'user_id'
    ];

    public function transacao()
    {
        return $this->belongsTo(Transacao::class);
    }
}

