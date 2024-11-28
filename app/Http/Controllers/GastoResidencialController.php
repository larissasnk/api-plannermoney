<?php

namespace App\Http\Controllers;

use App\Models\GastoResidencial;
use App\Models\Transacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GastoResidencialController extends Controller
{
    public function index()
    {
        $gastosResidenciais = GastoResidencial::all();
        $gastosResidenciais->load('transacao');

        return response()->json($gastosResidenciais, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'descricao' => 'required|string|max:255',
            'valor' => 'required|numeric',
            'data' => 'required|date',
            'status' => 'required|string|in:pago,pendente,vencido',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $dados = $request->all();

        $dados['tipo'] = 'saida';
        $dados['categoria'] = 'gasto-residencial';

        $transacao = Transacao::create($dados);

        $dados['transacao_id'] = $transacao->id;

        $gastoResidencial = GastoResidencial::create($dados);

        $gastoResidencial->load('transacao');
        return response()->json($gastoResidencial, 201);
    }

    public function show($id)
    {
        $gastoResidencial = GastoResidencial::find($id);
        if (!$gastoResidencial) {
            return response()->json(['error' => 'Gasto residencial não encontrado'], 404);
        }
        $gastoResidencial->load('transacao');
        return response()->json($gastoResidencial, 200);
    }

    public function update(Request $request, $id)
    {
        $gastoResidencial = GastoResidencial::find($id);
        if (!$gastoResidencial) {
            return response()->json(['error' => 'Gasto residencial não encontrado'], 404);
        }

        $validator = Validator::make($request->all(), [
            'descricao' => 'sometimes|required|string|max:255',
            'valor' => 'sometimes|required|numeric',
            'data' => 'sometimes|required|date',
            'status' => 'sometimes|required|string|in:pago,pendente,vencido',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Atualizar os dados da transação relacionada
        $transacao = $gastoResidencial->transacao;
        if ($transacao) {
            $transacao->update($request->only(['descricao', 'valor', 'data', 'status']));
        }

        // Atualizar os dados específicos do gasto residencial
        $gastoResidencial->update($request->only(['descricao', 'valor', 'data', 'status']));

        return response()->json($gastoResidencial->load('transacao'), 200);
    }

    public function destroy($id)
    {
        $gastoResidencial = GastoResidencial::find($id);
        if (!$gastoResidencial) {
            return response()->json(['error' => 'Gasto residencial não encontrado'], 404);
        }

        // Deleta a transação associada ao gasto residencial
        $gastoResidencial->transacao()->delete();

        // Deleta o próprio gasto residencial
        $gastoResidencial->delete();

        return response()->json(['message' => 'Gasto residencial e transação deletados com sucesso'], 200);
    }
}
