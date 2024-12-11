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
        $receitas = Receita::where('user_id', auth()->user()->id)->get();
        $receitas->load('transacao');

        return response()->json($receitas, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'data' => 'required|date',
            'valor' => 'nullable|numeric',
            'renda_extra_nome' => 'nullable|string|max:255',
            'renda_extra_valor' => 'nullable|numeric',
            'renda_eventual_nome' => 'nullable|string|max:255',
            'renda_eventual_valor' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Garantir que pelo menos um grupo está preenchido
        if (
            !$request->hasAny([
                'valor',
                'renda_extra_nome',
                'renda_extra_valor',
                'renda_eventual_nome',
                'renda_eventual_valor'
            ])
        ) {
            return response()->json([
                'error' => 'Pelo menos um dos grupos deve ser preenchido: receita mensal, renda extra ou renda eventual.'
            ], 422);
        }

        // Preparando os dados para a transação
        $dados = $request->all();
        $dados['descricao'] = 'renda mensal';
        $dados['categoria'] = 'entradas-mes';
        $dados['tipo'] = 'entrada';

     
        $dados['user_id'] = auth()->user()->id;

        $transacao = Transacao::create($dados);

        // Atribuindo o ID da transação à receita
        $dados['transacao_id'] = $transacao->id;

        // Criando a receita com o transacao_id
        $receita = Receita::create($dados);

        return response()->json(['success' => true, 'data' => $receita->load('transacao')], 201);
    }

    public function show($id)
    {
        $receita = Receita::find($id);
        if ($receita) {
            $receita->load('transacao');
        }

        return response()->json($receita, 200);
    }

    public function update(Request $request, $id)
    {
        // Encontra a receita
        $receita = Receita::find($id);
        if (!$receita) {
            return response()->json(['error' => 'Receita não encontrada'], 404);
        }

        $data = [];

        // Verifica e atualiza campos específicos para Renda Mensal
        if ($request->has('valor')) {
            $data['valor'] = $request->input('valor');
        }

        // Verifica e atualiza campos específicos para Renda Extra
        if ($request->has('renda_extra_nome') || $request->has('renda_extra_valor')) {
            $data['renda_extra_nome'] = $request->input('renda_extra_nome', null);
            $data['renda_extra_valor'] = $request->input('renda_extra_valor', null);
        }

        // Verifica e atualiza campos específicos para Renda Eventual
        if ($request->has('renda_eventual_nome') || $request->has('renda_eventual_valor')) {
            $data['renda_eventual_nome'] = $request->input('renda_eventual_nome', null);
            $data['renda_eventual_valor'] = $request->input('renda_eventual_valor', null);
        }

        // Retorna erro se nenhum dado válido foi passado
        if (empty($data)) {
            return response()->json(['error' => 'Nenhum campo para atualizar foi fornecido.'], 422);
        }

        // Atualiza a receita
        $receita->update($data);

        // Verifica se a transação está vazia e deleta se necessário
        // if (
        //     $receita->transacao &&
        //     $receita->transacao->valor === null &&
        //     $receita->transacao->renda_extra_valor === null &&
        //     $receita->transacao->renda_eventual_valor === null
        // ) {
        //     $receita->transacao->delete();
        // }

        return response()->json(['success' => true, 'data' => $receita], 200);
    }


    public function destroy($id)
    {
        // Localizar a receita pelo ID
        $receita = Receita::find($id);

        // Verificar se a receita existe
        if (!$receita) {
            return response()->json(['error' => 'Receita não encontrada'], 404);
        }

        // Excluir a transação associada, se existir
        if ($receita->transacao) {
            $receita->transacao->delete();
        }

        // Excluir a receita
        $receita->delete();

        return response()->json(['message' => 'Receita e transação deletadas com sucesso'], 200);
    }
}
