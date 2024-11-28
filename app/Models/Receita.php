<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Receita extends Model
{
    protected $table = 'receitas';

    protected $fillable = [
        'transacao_id',
        'renda_extra_nome',
        'renda_extra_valor',
        'renda_eventual_nome',
        'renda_eventual_valor',
    ];

    public function transacao()
    {
        return $this->belongsTo(Transacao::class);
    }
}
