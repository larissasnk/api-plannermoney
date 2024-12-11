<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Investimento extends Model
{
    protected $fillable = [
        'transacao_id',
        'taxa_retorno',
        'user_id'
    ];

    public function transacao()
    {
        return $this->belongsTo(Transacao::class);
    }
}

