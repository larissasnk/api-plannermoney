<?php

namespace App\Http\Controllers;

use App\Models\Receita;
use App\Models\Transacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReceitaController extends Controller
{
    public function index()
    {
        $receitas = Receita::all();
        $receitas->load('transacao');

        return response()->json($receitas, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'valor' => 'sometimes|required|numeric',
            'data' => 'required|date',
            'renda_extra_nome' => 'sometimes|string|max:255',
            'renda_extra_valor' => 'sometimes|numeric',
            'renda_eventual_nome' => 'sometimes|string|max:255',
            'renda_eventual_valor' => 'sometimes|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Preparando os dados para a transação
        $dados = $request->all();

        $dados['descricao'] = 'renda mensal';       
        $dados['categoria'] = 'entradas-mes';    
        $dados['tipo'] = 'entrada'; 

        $transacao = Transacao::create($dados);

        // Atribuindo o ID da transação à receita
        $dados['transacao_id'] = $transacao->id;

        // Criando a receita com o transacao_id
        $receita = Receita::create($dados);

        // $receita->load('transacao');
        // return response()->json($receita, 201);

        return response()->json(['success' => true, 'data' => $receita->load('transacao')], 201);
    }

    public function show($id)
    {
        $receita = Receita::find($id);
        if (!$receita) {
            return response()->json(['error' => 'Receita não encontrada'], 404);
        }

        $receita->load('transacao');
        return response()->json($receita, 200);
    }

    public function update(Request $request, $id)
    {
        $receita = Receita::find($id);
        if (!$receita) {
            return response()->json(['error' => 'Receita não encontrada'], 404);
        }

        $validator = Validator::make($request->all(), [
            'valor' => 'sometimes|required|numeric',
            'data' => 'sometimes|required|date',
            'renda_extra_nome' => 'sometimes|string|max:255',
            'renda_extra_valor' => 'sometimes|numeric',
            'renda_eventual_nome' => 'sometimes|string|max:255',
            'renda_eventual_valor' => 'sometimes|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $transacao = $receita->transacao;
        if ($transacao) {
            $transacao->update($request->only(['descricao', 'valor', 'data']));
        }

        $dadosAtualizados = $request->only(['valor', 'data', 'renda_extra_nome', 'renda_extra_valor', 'renda_eventual_nome', 'renda_eventual_valor']);
        $receita->update($dadosAtualizados);

        return response()->json($receita->load('transacao'), 200);
    }

    public function destroy($id)
    {
        $receita = Receita::find($id);
        if (!$receita) {
            return response()->json(['error' => 'Receita não encontrada'], 404);
        }

        $receita->transacao()->delete(); // Deleta a transação associada
        $receita->delete(); // Deleta a receita

        return response()->json(['message' => 'Receita e transação deletadas com sucesso'], 200);
    }
}
