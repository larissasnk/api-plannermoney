<?php

namespace App\Http\Controllers;

use App\Models\Metodo502030;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use OpenApi\Annotations as OA;

class Metodo502030Controller extends Controller
{
    public function index()
    {
        $metodos = Metodo502030::all();
        return response()->json($metodos, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tipo' => 'required|string',
            'nome' => 'required|string',
            'valor_previsto' => 'required|numeric',
            'valor_real' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $metodo = Metodo502030::create($request->all());
        return response()->json($metodo, 201);
    }

    public function show($id)
    {
        $metodo = Metodo502030::find($id);
        if (!$metodo) {
            return response()->json(['error' => 'Método 50/20/30 não encontrado'], 404);
        }
        return response()->json($metodo, 200);
    }

    public function update(Request $request, $id)
    {
        $metodo = Metodo502030::find($id);
        if (!$metodo) {
            return response()->json(['error' => 'Método 50/20/30 não encontrado'], 404);
        }

        $validator = Validator::make($request->all(), [
            'tipo' => 'sometimes|required|string',
            'nome' => 'sometimes|required|string',
            'valor_previsto' => 'sometimes|required|numeric',
            'valor_real' => 'sometimes|required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $metodo->update($request->all());
        return response()->json($metodo, 200);
    }

    public function destroy($id)
    {
        $metodo = Metodo502030::find($id);
        if (!$metodo) {
            return response()->json(['error' => 'Método 50/20/30 não encontrado'], 404);
        }

        $metodo->delete();
        return response()->json(['message' => 'Método 50/20/30 deletado com sucesso'], 200);
    }
}
