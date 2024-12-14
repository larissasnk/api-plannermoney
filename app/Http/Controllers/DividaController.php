<?php

namespace App\Http\Controllers;

use App\Models\Divida;
use App\Models\Transacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DividaController extends Controller
{
    // Retorna todas as dívidas pendentes com suas transações associadas
    public function index()
    {
        $dividas = Divida::where('user_id', auth()->user()->id)->with('transacao')->get();
        return response()->json($dividas, 200);
    }

    // Cria uma nova dívida
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'descricao' => 'required|string|max:255',
            'data' => 'required|date',
            'valor' => 'required|numeric',
            'quitado' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $dados = $request->all();

        $dados['tipo'] = 'saida'; // Define o tipo como saída
        $dados['categoria'] = 'divida-pendente'; // Categoria da transação
        $dados['user_id'] = auth()->user()->id;

        // Cria a transação associada
        $transacao = Transacao::create($dados);
        $dados['transacao_id'] = $transacao->id;

        // Cria a dívida
        $divida = Divida::create($dados);
        return response()->json($divida->load('transacao'), 201);
    }

    // Retorna os detalhes de uma dívida específica
    public function show($id)
    {
        $divida = Divida::with('transacao')->find($id);
        if (!$divida) {
            return response()->json(['error' => 'Dívida não encontrada'], 404);
        }
        return response()->json($divida, 200);
    }

    // Atualiza uma dívida existente
    public function update(Request $request, $id)
    {
        $divida = Divida::findOrFail($id);
    
        // Valida os campos enviados
        $validator = Validator::make($request->all(), [
            'transacao.descricao' => 'sometimes|required|string|max:255',
            'transacao.valor' => 'sometimes|required|numeric',
            'transacao.data' => 'sometimes|required|date',
            'quitado' => 'sometimes|required|boolean',
        ]);
    
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
    
        // Atualiza os dados da transação associada
        if ($request->has('transacao')) {
            $divida->transacao->update($request->input('transacao'));
        }
    
        // Atualiza o status quitado, se enviado
        if ($request->has('quitado')) {
            $divida->update(['quitado' => $request->input('quitado')]);
        }
    
        // Retorna a dívida com os dados atualizados
        return response()->json($divida->load('transacao'), 200);
    }
    

    // Exclui uma dívida e sua transação associada
    public function destroy($id)
    {
        $divida = Divida::find($id);
        if (!$divida) {
            return response()->json(['error' => 'Dívida não encontrada'], 404);
        }

        // Exclui a transação associada
        $divida->transacao()->delete();
        // Exclui a dívida
        $divida->delete();

        return response()->json(['message' => 'Dívida e transação deletadas com sucesso'], 200);
    }
}
