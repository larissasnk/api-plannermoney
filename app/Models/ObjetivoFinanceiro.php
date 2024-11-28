<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ObjetivoFinanceiro extends Model
{
    protected $table = 'objetivo_financeiro';

    protected $fillable = [
        'tipo_objetivo',
        'data',
        'valor',
        'plano',
    ];
}
