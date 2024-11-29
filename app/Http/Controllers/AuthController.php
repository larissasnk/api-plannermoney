<?php
namespace App\Http\Controllers;

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

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user,
            'access_token' => $token,
        ], 201);
    }

    // Login para gerar o access token
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        
        // Tenta gerar o Access Token com as credenciais do usuário
        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Gera o Refresh Token (o JWT retorna o mesmo token)
        return response()->json([
            'access_token' => $token,
        ]);
    }
    
    public function logout()
    {
        JWTAuth::logout();

        return response()->json(['message' => 'Logout successful'], 200);
    }

    // Refresh para gerar um novo access token usando o refresh token
    public function refresh()
    {
        try {
            $token = JWTAuth::parseToken()->refresh();
            return response()->json(['access_token' => $token]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Refresh token is invalid'], 401);
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