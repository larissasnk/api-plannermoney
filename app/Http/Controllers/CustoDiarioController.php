<?php

namespace App\Http\Controllers;

use App\Models\CustoDiario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use OpenApi\Annotations as OA;

class CustoDiarioController extends Controller
{
    public function index()
    {
        $custosDiarios = CustoDiario::all();
        return response()->json($custosDiarios, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'alimentacao_valor' => 'sometimes|required|numeric',
            'passeio_valor' => 'sometimes|required|numeric',
            'transporte_valor' => 'sometimes|required|numeric',
            'extra_valor' => 'sometimes|required|numeric',
            'data_diaria' => 'required|date',
            'planejamento_viagem_id' => 'required|exists:planejamento_viagem,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $custoDiario = CustoDiario::create($request->all());
        return response()->json($custoDiario, 201);
    }

    public function show($id)
    {
        $custoDiario = CustoDiario::find($id);
        if (!$custoDiario) {
            return response()->json(['error' => 'Custo diário não encontrado'], 404);
        }
        return response()->json($custoDiario, 200);
    }

    public function update(Request $request, $id)
    {
        $custoDiario = CustoDiario::find($id);
        if (!$custoDiario) {
            return response()->json(['error' => 'Custo diário não encontrado'], 404);
        }

        $validator = Validator::make($request->all(), [
            'alimentacao_valor' => 'sometimes|required|numeric',
            'passeio_valor' => 'sometimes|required|numeric',
            'transporte_valor' => 'sometimes|required|numeric',
            'extra_valor' => 'sometimes|required|numeric',
            'data_diaria' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $custoDiario->update($request->all());
        return response()->json($custoDiario, 200);
    }

    public function destroy($id)
    {
        $custoDiario = CustoDiario::find($id);
        if (!$custoDiario) {
            return response()->json(['error' => 'Custo diário não encontrado'], 404);
        }

        $custoDiario->delete();
        return response()->json(['message' => 'Custo diário deletado com sucesso'], 200);
    }
}
