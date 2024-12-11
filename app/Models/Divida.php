<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Divida extends Model
{
    use HasFactory;

    protected $fillable = [
        'transacao_id',
        'quitado',
        'user_id'
    ];

    // Relacionamento com a tabela de transações
    public function transacao()
    {
        return $this->belongsTo(Transacao::class);
    }
}
