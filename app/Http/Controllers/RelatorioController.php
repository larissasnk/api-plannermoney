<?php

namespace App\Http\Controllers;

use App\Models\Transacao;
use App\Models\PlanejamentoViagem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RelatorioController extends Controller
{
    public function consolidado(Request $request)
    {
        // Buscar todas as transações (entradas e saídas)
        $transacoes = Transacao::where('user_id', $request->user()->id)
            ->get()
            ->groupBy('categoria');

        // Buscar dados do planejamento de viagem
        $planejamentos = PlanejamentoViagem::where('user_id', $request->user()->id)->with('custosDiarios')->get();

        $viagemRelatorio = $planejamentos->map(function ($planejamento) {
            $totalCustosDiarios = $planejamento->custosDiarios->sum(function ($custoDiario) {
                return $custoDiario->alimentacao_valor +
                    $custoDiario->passeio_valor +
                    $custoDiario->transporte_valor +
                    $custoDiario->extra_valor;
            });

            $total = $planejamento->valor_hospedagem + $totalCustosDiarios;

            return [
                'id' => $planejamento->id,
                'nome_viagem' => $planejamento->nome_viagem,
                'valor_hospedagem' => $planejamento->valor_hospedagem,
                'total_custos_diarios' => $totalCustosDiarios,
                'valor_total' => $total,
                'categoria' => 'Planejamento de Viagem',
            ];
        });

        // Estruturar dados para retorno
        $relatorio = [
            'transacoes' => $transacoes,
            'planejamento_viagem' => $viagemRelatorio,
        ];

        return response()->json($relatorio, 200);
    }
}
