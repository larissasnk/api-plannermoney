<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Metodo502030 extends Model
{
    protected $table = 'metodo_50_20_30';

    protected $fillable = [
        'tipo',
        'nome',
        'valor_previsto',
        'valor_real',
    ];
}
