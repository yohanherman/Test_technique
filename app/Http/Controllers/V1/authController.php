<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\V1\Controller;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    public function registerAdmin(RegisterUserRequest $request): JsonResponse
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if ($user) {
            $response = [
                'user' => $user,
                'success' => true,
                'status' => 200
            ];
            return response()->json($response, 201);
        }
        $response = [
            'user' => $user,
            'success' => false,
            'status' => 501
        ];
        return response()->json($response, 500);
    }

    public function login(LoginUserRequest $request)
    {
        $credentials = $request->only('email', 'password');
        $token = Auth::attempt($credentials);
        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'unauthorized',
            ], 401);
        }

        $user = Auth::user();
        return response()->json([
            'user' => $user,
            'status' => 200,
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
            ],
            'success' => true
        ]);
    }

    public function refresh()
    {
        $newToken = Auth::refresh();
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
            'authorisation' => [
                'token' => $newToken,
                'type' => 'bearer',
                'expires_in' => Auth::factory()->getTTL() * 60
            ],
            'message' => 'token successfully refreshed',
        ]);
    }


    public function logout()
    {
        Auth::logout();
        return response()->json([
            'status' => '200',
            'success' => true,
            'message' => 'Successfully logged out',
        ]);
    }
}
