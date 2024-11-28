<?php

namespace App\Http\Controllers;

use App\Models\GastoFamiliar;
use App\Models\Transacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GastoFamiliarController extends Controller
{
 
    public function index()
    {
        $gastosFamiliares = GastoFamiliar::with('transacao')->get();
        return response()->json($gastosFamiliares, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tipo_membro' => 'required|string|in:Conjugue,Filho',
            'nome_membro' => 'sometimes|string',
            'descricao' => 'required|string|max:255',
            'data' => 'required|date',
            'gasto_previsto' => 'required|numeric',
            'gasto_realizado' => 'sometimes|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $dados = $request->all();

        $dados['tipo'] = 'saida'; // Define que é um gasto
        $dados['categoria'] = 'gasto-familiar';
        $dados['valor'] = null;

        $transacao = Transacao::create($dados);
        $dados['transacao_id'] = $transacao->id;

        $gastoFamiliar = GastoFamiliar::create($dados);
        return response()->json($gastoFamiliar->load('transacao'), 201);
    }

    public function show($id)
    {
        $gastoFamiliar = GastoFamiliar::with('transacao')->find($id);
        if (!$gastoFamiliar) {
            return response()->json(['error' => 'Gasto Familiar não encontrado'], 404);
        }
        return response()->json($gastoFamiliar, 200);
    }

    public function update(Request $request, $id)
    {
        $gastoFamiliar = GastoFamiliar::find($id);
        if (!$gastoFamiliar) {
            return response()->json(['error' => 'Gasto Familiar não encontrado'], 404);
        }

        $validator = Validator::make($request->all(), [
            'tipo_membro' => 'sometimes|required|string|in:Conjugue,Filho',
            'nome_membro' => 'sometimes|string',
            'descricao' => 'sometimes|required|string|max:255',
            'data' => 'sometimes|required|date',
            'gasto_previsto' => 'sometimes|required|numeric',
            'gasto_realizado' => 'sometimes|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Atualizar transação associada, se necessário
        if ($gastoFamiliar->transacao) {
            $gastoFamiliar->transacao->update($request->only(['descricao']));
        }

        $gastoFamiliar->update($request->all());
        return response()->json($gastoFamiliar->load('transacao'), 200);
    }

    public function destroy($id)
    {
        $gastoFamiliar = GastoFamiliar::find($id);
        if (!$gastoFamiliar) {
            return response()->json(['error' => 'Gasto Familiar não encontrado'], 404);
        }

        $gastoFamiliar->transacao()->delete();
        $gastoFamiliar->delete();

        return response()->json(['message' => 'Gasto Familiar e transação deletados com sucesso'], 200);
    }
}
