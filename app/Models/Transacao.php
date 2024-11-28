<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transacao extends Model
{
    protected $table = 'transacoes';

    protected $fillable = [
        'descricao',
        'valor',
        'data',
        'tipo',
        'categoria',
    ];
}
