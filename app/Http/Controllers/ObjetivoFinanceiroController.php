<?php

namespace App\Http\Controllers;

use App\Models\ObjetivoFinanceiro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use OpenApi\Annotations as OA;

class ObjetivoFinanceiroController extends Controller
{
    public function index()
    {
        $objetivos = ObjetivoFinanceiro::where('user_id', auth()->user()->id)->get();
        return response()->json($objetivos, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tipo_objetivo' => 'required|string',
            'valor' => 'required|numeric',
            'data' => 'required|date',
            'plano' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $dados = $request->all();
        $dados['user_id'] = auth()->user()->id;

        $objetivo = ObjetivoFinanceiro::create($dados);
        return response()->json($objetivo, 201);
    }
    public function show($id)
    {
        $objetivo = ObjetivoFinanceiro::find($id);
        if (!$objetivo) {
            return response()->json(['error' => 'Objetivo financeiro não encontrado'], 404);
        }
        return response()->json($objetivo, 200);
    }

    public function update(Request $request, $id)
    {
        $objetivo = ObjetivoFinanceiro::find($id);
        if (!$objetivo) {
            return response()->json(['error' => 'Objetivo financeiro não encontrado'], 404);
        }

        $validator = Validator::make($request->all(), [
            'tipo_objetivo' => 'sometimes|required|string',
            'valor' => 'sometimes|required|numeric',
            'prazo' => 'sometimes|required|date',
            'descricao' => 'sometimes|required|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $objetivo->update($request->all());
        return response()->json($objetivo, 200);
    }

    public function destroy($id)
    {
        $objetivo = ObjetivoFinanceiro::find($id);
        if (!$objetivo) {
            return response()->json(['error' => 'Objetivo financeiro não encontrado'], 404);
        }

        $objetivo->delete();
        return response()->json(['message' => 'Objetivo financeiro deletado com sucesso'], 200);
    }
}
