<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GastoResidencial extends Model
{
    protected $table = 'gastos_residenciais';

    protected $fillable = [
        'transacao_id',
        'status',
        'user_id'
    ];

    public function transacao()
    {
        return $this->belongsTo(Transacao::class);
    }
}
