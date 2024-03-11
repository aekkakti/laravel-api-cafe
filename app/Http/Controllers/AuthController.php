<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
            $credentials = $request->only('login', 'password');
            $user = User::where('login', $credentials['login'])->first();

            if (!$user || $user->password !== $credentials['password']) {
                return response()->json(['code' => '401' ,'error' => 'Authentication failed']);
            }

            $token = $user->createToken('AuthToken')->plainTextToken;
            return response()->json(['token' => $token], 200);
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();
        return response()->json(['message' => 'logout'], 200);
    }
}
