<?php

namespace App\Http\Controllers;

use App\Models\AquisiacaoBem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AquisicaoBemController extends Controller
{

    public function index()
    {
        $bens = AquisiacaoBem::where('user_id', auth()->user()->id)->get();
        return response()->json($bens, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|string|',
            'nome' => 'required|string|max:255',
            'valor' => 'required|numeric',
            'data_aquisicao' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $dados = $request->all();
        $dados['user_id'] = auth()->user()->id;

        $bem = AquisiacaoBem::create($dados);
        return response()->json($bem, 201);
    }

    public function show($id)
    {
        $bem = AquisiacaoBem::find($id);
        if (!$bem) {
            return response()->json(['error' => 'Bem não encontrado'], 404);
        }
        return response()->json($bem, 200);
    }

    public function update(Request $request, $id)
    {
        $bem = AquisiacaoBem::find($id);
        if (!$bem) {
            return response()->json(['error' => 'Bem não encontrado'], 404);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'sometimes|required|string|',
            'nome' => 'sometimes|required|string|max:255',
            'valor' => 'sometimes|required|numeric',
            'data_aquisicao' => 'sometimes|required|date',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $bem->update($request->all());
        return response()->json($bem, 200);
    }

    public function destroy($id)
    {
        $bem = AquisiacaoBem::find($id);
        if (!$bem) {
            return response()->json(['error' => 'Bem não encontrado'], 404);
        }

        $bem->delete();
        return response()->json(['message' => 'Bem deletado com sucesso'], 200);
    }
}
