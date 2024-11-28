<?php

namespace App\Http\Controllers;

use App\Models\Transacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use OpenApi\Annotations as OA;

class TransacaoController extends Controller
{
    public function index()
    {
        $transacoes = Transacao::all();
        return response()->json($transacoes, 200);
    }

    public function create()
    {
        // Normalmente utilizado para renderizar uma view de criação no front-end
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'descricao' => 'required|string|max:255',
            'valor' => 'sometimes|required|numeric',
            'data' => 'required|date',
            'tipo' => 'required|string|in:entrada,saida',
            'categoria' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $transacao = Transacao::create($request->all());
        return response()->json($transacao, 201);
    }
   
    public function show($id)
    {
        $transacao = Transacao::find($id);
        if (!$transacao) {
            return response()->json(['error' => 'Transação não encontrada'], 404);
        }
        return response()->json($transacao, 200);
    }

    public function edit($id)
    {
        // Normalmente utilizado para renderizar uma view de edição no front-end
    }
    public function update(Request $request, $id)
    {
        $transacao = Transacao::find($id);
        if (!$transacao) {
            return response()->json(['error' => 'Transação não encontrada'], 404);
        }

        $validator = Validator::make($request->all(), [
            'descricao' => 'sometimes|required|string|max:255',
            'valor' => 'sometimes|required|numeric',
            'data' => 'sometimes|required|date',
            'tipo' => 'sometimes|required|string|in:entrada,"saida"',
            'categoria' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $transacao->update($request->all());
        return response()->json($transacao, 200);
    }

    public function destroy($id)
    {
        $transacao = Transacao::find($id);
        if (!$transacao) {
            return response()->json(['error' => 'Transação não encontrada'], 404);
        }

        $transacao->delete();
        return response()->json(['message' => 'Transação deletada com sucesso'], 200);
    }
}
