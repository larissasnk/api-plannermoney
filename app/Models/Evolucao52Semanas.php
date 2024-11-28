<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evolucao52Semanas extends Model
{
    protected $table = 'evolucao_52_semanas';

    protected $fillable = [
        'status',
        'semana',
        'valor_deposito',
        'valor_acumulado',
    ];
}
