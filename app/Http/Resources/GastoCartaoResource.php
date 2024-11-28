<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class GastoCartaoResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'descricao' => $this->descricao,
            'data' => $this->data,
            'tipo_controle' => $this->tipo_controle,
            'valor_total' => $this->valor_total,
            'tipo' => $this->tipo,
            'created_at' => Carbon::make($this->created_at)->format('Y-m-d'),
            'updated_at' => Carbon::make($this->updated_at)->format('Y-m-d'),
        ];
    }
}
