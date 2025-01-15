<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\V1\Controller;
use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function registerAdmin(RegisterUserRequest $request): JsonResponse
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'roles' => $request->roles
        ]);

        if ($user) {
            $response = [
                'user' => $user,
                'success' => true,
                'status' => 200
            ];
            return response()->json($response, 201);
        }
        $response =[
            'user' => $user,
            'success' => false,
            'status' => 501
        ];
        return response()->json($response, 500);

        
    }
}
