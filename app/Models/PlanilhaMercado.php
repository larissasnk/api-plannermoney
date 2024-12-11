<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanilhaMercado extends Model
{
    protected $table = 'planilha_mercado';

    protected $fillable = [
        'nome_item',
        'valor',
        'tipo_unidade',
        'quantidade',
        'user_id',
    ];
}
