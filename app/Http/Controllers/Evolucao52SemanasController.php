<?php

namespace App\Http\Controllers;

use App\Models\Evolucao52Semanas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use OpenApi\Annotations as OA;

class Evolucao52SemanasController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $user->generate52Weeks();

        $evolucoes = Evolucao52Semanas::where('user_id', $user->id)->get();
        return response()->json($evolucoes, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'semana' => 'required|integer',
            'status'=> 'required|integer',
            'valor_deposito' => 'required|numeric',
            'valor_acumulado' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $dados = $request->all();
        $dados['user_id'] = auth()->user()->id;

        $evolucao = Evolucao52Semanas::create($dados);
        return response()->json($evolucao, 201);
    }

    public function show($id)
    {
        $evolucao = Evolucao52Semanas::find($id);
        if (!$evolucao) {
            return response()->json(['error' => 'Evolução não encontrada'], 404);
        }
        return response()->json($evolucao, 200);
    }

    public function update(Request $request, $id)
    {
        $evolucao = Evolucao52Semanas::find($id);
        if (!$evolucao) {
            return response()->json(['error' => 'Evolução não encontrada'], 404);
        }

        $validator = Validator::make($request->all(), [
            'status'=> 'sometimes|required|integer',
            'semana' => 'sometimes|required|integer',
            'valor_deposito' => 'sometimes|required|numeric',
            'valor_acumulado' => 'sometimes|required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $evolucao->update($request->all());
        return response()->json($evolucao, 200);
    }

    public function destroy($id)
    {
        $evolucao = Evolucao52Semanas::find($id);
        if (!$evolucao) {
            return response()->json(['error' => 'Evolução não encontrada'], 404);
        }

        $evolucao->delete();
        return response()->json(['message' => 'Evolução deletada com sucesso'], 200);
    }
}
