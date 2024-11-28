<?php

namespace App\Http\Controllers;

use App\Models\PlanejamentoViagem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use OpenApi\Annotations as OA;

class PlanejamentoViagemController extends Controller
{
    public function index()
    {
        $planejamentos = PlanejamentoViagem::all();
        return response()->json($planejamentos, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nome_viagem' => 'required|string|max:255',
            'inicio_viagem' => 'required|date',
            'termino_viagem' => 'required|date|after_or_equal:inicio_viagem',
            'valor_hospedagem' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Criação do planejamento da viagem
        $viagem = PlanejamentoViagem::create($request->all());

        // Atualizar o valor total da viagem (inicialmente é só o valor da hospedagem)
        $viagem->valor_total_viagem = $viagem->valor_hospedagem;
        $viagem->save();

        return response()->json($viagem, 201);
    }

    public function show($id)
    {
        $viagem = PlanejamentoViagem::with('custosDiarios')->find($id);

        if (!$viagem) {
            return response()->json(['error' => 'Planejamento de viagem não encontrado'], 404);
        }

        $totalGastos = $viagem->custosDiarios->sum(function ($custoDiario) {
            return $custoDiario->alimentacao_valor +
                $custoDiario->passeio_valor +
                $custoDiario->transporte_valor +
                $custoDiario->extra_valor;
        });

        $viagem->valor_total_viagem = $viagem->valor_hospedagem + $totalGastos;

        return response()->json($viagem, 200);
    }

    public function update(Request $request, $id)
    {
        $viagem = PlanejamentoViagem::find($id);
        if (!$viagem) {
            return response()->json(['error' => 'Planejamento de viagem não encontrado'], 404);
        }

        $validator = Validator::make($request->all(), [
            'nome_viagem' => 'sometimes|required|string|max:255',
            'inicio_viagem' => 'sometimes|required|date',
            'termino_viagem' => 'sometimes|required|date|after_or_equal:inicio_viagem',
            'valor_hospedagem' => 'sometimes|required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $viagem->update($request->all());
        $viagem->valor_total_viagem = $viagem->valor_hospedagem + $viagem->custosDiarios->sum(function ($custoDiario) {
            return $custoDiario->alimentacao_valor +
                $custoDiario->passeio_valor +
                $custoDiario->transporte_valor +
                $custoDiario->extra_valor;
        });

        $viagem->save();

        return response()->json($viagem, 200);
    }

    public function destroy($id)
    {
        // Encontrar o planejamento de viagem
        $planejamentoViagem = PlanejamentoViagem::find($id);

        if (!$planejamentoViagem) {
            return response()->json(['error' => 'Planejamento de viagem não encontrado'], 404);
        }

        // Apagar as diárias associadas ao planejamento de viagem
        $planejamentoViagem->custosDiarios()->delete();

        // Apagar o planejamento de viagem
        $planejamentoViagem->delete();

        // Retornar a mensagem de sucesso
        return response()->json(['message' => 'Planejamento de viagem e diárias deletados com sucesso'], 200);
    }
}
