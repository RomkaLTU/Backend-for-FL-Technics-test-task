<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterUser;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ApiController extends Controller
{
    public function register( RegisterUser $request )
    {
        $validated = $request->validated();

        $user = User::create([
            'email' => $validated['email'],
            'name' => $validated['fullname'],
            'password' => Hash::make($validated['password']),
        ]);

        return $user;
    }

    public function login()
    {
        $credentials = request(['email', 'password']);

        if ( !$token = auth('api')->attempt($credentials) ) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json([
            'token' => $token,
            'expires' => auth('api')->factory()->getTTL() * 60,
        ]);
    }
}
