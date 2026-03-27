<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(LoginRequest $request)
    {
        // ds($request->validated());
        $credentials = $request->only('email', 'password');

        if (auth()->attempt($credentials)) {
            $token  = Auth::user()->createToken('api');
            return response()->json([
                'message' => 'Login successful',
                'token' => $token->plainTextToken
            ]);
        }
        return response()->json(['message' => 'Invalid credentials'], 401);
    }
}
