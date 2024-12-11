<?php

namespace App\Http\Controllers;

use App\Models\Investimento;
use App\Models\Transacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InvestimentoController extends Controller
{
    public function index()
    {
        $investimentos = Investimento::where('user_id', auth()->user()->id)->with('transacao')->get();
        return response()->json($investimentos, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'descricao' => 'required|string|max:255',
            'valor' => 'required|numeric',
            'data' => 'required|date',
            'taxa_retorno' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $transacao = Transacao::create([
            'tipo' => 'saida',
            'categoria' => 'investimento',
            'descricao' => $request->input('descricao'),
            'valor' => $request->input('valor'),
            'data' => $request->input('data'),
            'user_id' => $request->user()->id,
        ]);
        
        $investimento = Investimento::create([
            'transacao_id' => $transacao->id,
            'taxa_retorno' => $request->input('taxa_retorno'),
            'user_id' => $request->user()->id,
        ]);

        return response()->json($investimento->load('transacao'), 201);
    }

    public function show($id)
    {
        $investimento = Investimento::with('transacao')->find($id);

        if (!$investimento) {
            return response()->json(['error' => 'Investimento não encontrado'], 404);
        }

        return response()->json($investimento, 200);
    }

    public function update(Request $request, $id)
    {
        $investimento = Investimento::find($id);

        if (!$investimento) {
            return response()->json(['error' => 'Investimento não encontrado'], 404);
        }

        $validator = Validator::make($request->all(), [
            'descricao' => 'sometimes|string|max:255',
            'valor' => 'sometimes|numeric',
            'data' => 'sometimes|date',
            'taxa_retorno' => 'sometimes|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $investimento->transacao->update($request->only(['descricao', 'valor', 'data']));
        $investimento->update($request->only(['taxa_retorno']));

        return response()->json($investimento->load('transacao'), 200);
    }

    public function destroy($id)
    {
        $investimento = Investimento::find($id);

        if (!$investimento) {
            return response()->json(['error' => 'Investimento não encontrado'], 404);
        }

        $investimento->transacao->delete();
        $investimento->delete();

        return response()->json(['message' => 'Investimento excluído com sucesso'], 200);
    }

    public function resgatar($id)
    {
        $investimento = Investimento::with('transacao')->find($id);

        if (!$investimento) {
            return response()->json(['error' => 'Investimento não encontrado'], 404);
        }

        $valorInvestido = $investimento->transacao->valor;
        $taxaRetorno = $investimento->taxa_retorno;
        $valorRetorno = $valorInvestido + ($valorInvestido * $taxaRetorno / 100);

        Transacao::create([
            'tipo' => 'entrada',
            'categoria' => 'resgate-investimento',
            'descricao' => 'Resgate do investimento: ' . $investimento->transacao->descricao,
            'valor' => $valorRetorno,
            'data' => now(),
        ]);

        return response()->json(['message' => 'Investimento resgatado com sucesso', 'valor_retorno' => $valorRetorno], 200);
    }
}

