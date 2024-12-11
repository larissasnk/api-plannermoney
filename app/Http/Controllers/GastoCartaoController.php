<?php

namespace App\Http\Controllers;

use App\Models\GastoCartao;
use App\Models\Transacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class GastoCartaoController extends Controller
{
    public function index()
    {
        $gastosCartao = GastoCartao::where('user_id', auth()->user()->id)->get();
        $gastosCartao->load('transacao');

        return response()->json($gastosCartao, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'descricao' => 'required|string|max:255',
            'valor' => 'required|numeric',
            'data' => 'required|date',
            'parcelado' => 'required|bool',
            'quantidade_parcela' => 'required_if:parcelado,true|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $dados = $request->all();
        $dados['tipo'] = 'saida';
        $dados['categoria'] = 'gasto-cartao';
        $dados['user_id'] = auth()->user()->id;

        $transacao = Transacao::create($dados);

        $dados['transacao_id'] = $transacao->id;

        $gastoCartao = GastoCartao::create($dados);
        $gastoCartao->load('transacao');
        return response()->json($gastoCartao, 201);
    }

    public function show($id)
    {
        $gastoCartao = GastoCartao::find($id);
        if (!$gastoCartao) {
            return response()->json(['error' => 'Gasto com cartão não encontrado'], 404);
        }
        $gastoCartao->load('transacao');
        return response()->json($gastoCartao, 200);
    }

    public function update(Request $request, $id)
    {
        $gastoCartao = GastoCartao::find($id);
        if (!$gastoCartao) {
            return response()->json(['error' => 'Gasto com cartão não encontrado'], 404);
        }

        $validator = Validator::make($request->all(), [
            'descricao' => 'sometimes|required|string|max:255',
            'valor' => 'sometimes|required|numeric',
            'data' => 'sometimes|required|date',
            'parcelado' => 'sometimes|required|bool',
            'quantidade_parcela' => 'nullable|integer|min:1|required_if:parcelado,true',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $gastoCartao->update($request->all());
        $gastoCartao->transacao->update($request->all());

        $gastoCartao->load('transacao');
        return response()->json($gastoCartao, 200);
    }

    public function destroy($id)
    {
        $gastoCartao = GastoCartao::find($id);
        if (!$gastoCartao) {
            return response()->json(['error' => 'Gasto com cartão não encontrado'], 404);
        }

        $gastoCartao->transacao()->delete();
        $gastoCartao->delete();

        return response()->json(['message' => 'Gasto com cartão e transação deletados com sucesso'], 200);
    }
}
