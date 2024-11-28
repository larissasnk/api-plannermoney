<?php

namespace App\Http\Controllers;

use App\Models\PlanilhaMercado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use OpenApi\Annotations as OA;

class PlanilhaMercadoController extends Controller
{
    public function index()
    {
        $mercado = PlanilhaMercado::all();
        return response()->json($mercado, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nome_item'=> 'required|string|max:255',
            'valor'=> 'required|numeric',
            'quantidade'=> 'required|numeric',
            'tipo_unidade'=> 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $mercado = PlanilhaMercado::create($request->all());
        return response()->json($mercado, 201);
    }

    public function show($id)
    {
        $mercado = PlanilhaMercado::find($id);
        if (!$mercado) {
            return response()->json(['error' => 'Produto não encontrado'], 404);
        }
        return response()->json($mercado, 200);
    }

    public function update(Request $request, $id)
    {
        $mercado = PlanilhaMercado::find($id);
        if (!$mercado) {
            return response()->json(['error' => 'Produto não encontrado'], 404);
        }

        $validator = Validator::make($request->all(), [
            'nome_item'=> 'sometimes|required|string|max:255',
            'valor'=> 'sometimes|required|numeric',
            'quantidade'=> 'sometimes|required|numeric',
            'tipo_unidade'=> 'sometimes|required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $mercado->update($request->all());
        return response()->json($mercado, 200);
    }

    public function destroy($id)
    {
        $mercado = PlanilhaMercado::find($id);
        if (!$mercado) {
            return response()->json(['error' => 'Produto não encontrado'], 404);
        }

        $mercado->delete();
        return response()->json(['message' => 'Produto deletado com sucesso'], 200);
    }
}
