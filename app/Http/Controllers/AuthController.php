<?php

namespace App\Http\Controllers;

use App\Models\Evolucao52Semanas;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    // Registro de um novo usuário
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed', // password_confirmation deve ser enviado
        ]);

        // Criação do usuário
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Hash da senha
        ]);

        // Gera um token automaticamente após o registro
        $token = JWTAuth::fromUser($user);

        $user->generate52Weeks();

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user,
            'access_token' => $token,
        ], 201);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user(); // Obtém o usuário autenticado

        // Valida os dados enviados
        $request->validate([
            'name' => 'string|max:255',
            'email' => 'string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed', // password_confirmation deve ser enviado apenas se 'password' for preenchido
        ]);

        // Atualiza os dados do usuário
        $user->name = $request->name ?? $user->name;
        $user->email = $request->email ?? $user->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user,
        ]);
    }


    // Login para gerar o access token
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        // Tenta gerar o Access Token com as credenciais do usuário
        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = auth()->user();

        // Retorna o token e os dados do usuário
        return response()->json([
            'access_token' => $token,
            'user' => $user,
        ]);
    }

    public function logout()
    {
        JWTAuth::logout();

        return response()->json(['message' => 'Logout successful'], 200);
    }

    // Refresh para gerar um novo access token usando o refresh token
    public function refresh(Request $request)
    {
        try {
            // Renova o token
            $newToken = JWTAuth::parseToken()->refresh();

            // Configura o novo token para o contexto de autenticação
            JWTAuth::setToken($newToken);
            // $user = JWTAuth::toUser($newToken);

            return response()->json([
                'access_token' => $newToken,
                // 'user' => $user,
            ]);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['error' => 'Refresh token is invalid'], 401);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while refreshing the token'], 500);
        }
    }


    /**
     * Retorna os dados do usuário logado.
     */
    public function getUser(Request $request)
    {
        return response()->json($request->user());
    }
}


/*

exemplo register "/api/register":
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}

response:
{
  "message": "User registered successfully",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "created_at": "2024-11-28T12:34:56.000000Z",
    "updated_at": "2024-11-28T12:34:56.000000Z"
  },
  "access_token": "your_generated_access_token"
}

*/