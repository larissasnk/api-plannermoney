<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModelBase extends Model
{
    protected $fillable = [
        'descricao',
        'data',
        'tipo_controle',
        'valor_total'
    ];
}
