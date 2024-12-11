<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AquisiacaoBem extends Model
{
    protected $table = 'aquisicao_bem';
    
    protected $fillable = [
        'status',
        'nome',
        'valor',
        'data_aquisicao',
        'user_id'
    ];
}
