<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\V1\Controller;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;



/**
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="email", type="string", example="user@example.com"),
 *     @OA\Property(property="password", type="string", example="password1234"),
 *     @OA\Property(property="roles", type="string", example="user"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-16T12:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-01-16T12:00:00Z")
 * )
 */
class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/admin/auth/register",
     *     summary="Register a new admin user",
     *     description="This route allows registering a new admin user with a name, email, and password.",
     *     operationId="registerAdmin",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "password"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="admin@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User successfully registered",
     *         @OA\JsonContent(
     *             @OA\Property(property="user", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="email", type="string", example="admin@example.com"),
     *                 @OA\Property(property="created_at", type="string", format="datetime", example="2025-01-16T12:00:00.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="datetime", example="2025-01-16T12:00:00.000000Z")
     *             ),
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error while registering user",
     *         @OA\JsonContent(
     *             @OA\Property(property="user", type="null"),
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=501)
     *         )
     *     )
     * )
     */
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
                'status' => 201
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



    /**
     * @OA\Post(
     *     path="/api/admin/auth/login",
     *     summary="Authenticate a user",
     *     description=" allows a user to authenticate and to receive a token.",
     *     operationId="login",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successfully connected",
     *         @OA\JsonContent(
     *             @OA\Property(property="user", type="object", 
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="email", type="string", example="user@example.com"),
     *                 @OA\Property(property="roles", type="string", example="admin"),
     *             
     *             ),
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="authorization", type="object",
     *                 @OA\Property(property="token", type="string", example="eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."),
     *                 @OA\Property(property="type", type="string", example="bearer")
     *             ),
     *             @OA\Property(property="success", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="authentication failed",
     *         @OA\JsonContent(
     *             @OA\Property(property="status_code", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Email or password incorrect"),
     *             @OA\Property(property="status", type="integer", example=400)
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation faild",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="email", type="array",
     *                     @OA\Items(type="string", example="The email field is required.")
     *                 ),
     *                 @OA\Property(property="password", type="array",
     *                     @OA\Items(type="string", example="The password field is required.")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function login(LoginUserRequest $request)
    {
        $credentials = $request->only('email', 'password');
        $token = Auth::attempt($credentials);
        if (!$token) {
            return response()->json([
                'success' => 'false',
                'message' => 'Email or password incorrect',
                'status' => 400
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



    /**
     * @OA\Post(
     *     path="/api/admin/auth/logout",
     *     summary="Logout a user",
     *     description="allows to disconnect an authenticated user.",
     *     tags={"Authentication"},
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     @OA\Response(
     *         response=200,
     *         description="Successfully Disconnected",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="200"),
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Successfully logged out")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="401"),
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     )
     * )
     */

    public function logout()
    {
        Auth::logout();
        return response()->json([
            'status' => '200',
            'success' => true,
            'message' => 'Successfully logged out',
        ]);
    }



    /**
     * @OA\Post(
     *     path="/api/admin/auth/refresh", 
     *     summary="Refresh the authentication token",
     *     description="This endpoint refreshes the JWT token for the authenticated user.",
     *     operationId="refreshToken",
     *     tags={"Authentication"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Token successfully refreshed",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="user", type="object", ref="#/components/schemas/User"),
     *             @OA\Property(
     *                 property="authorisation",
     *                 type="object",
     *                 @OA\Property(property="token", type="string", example="new_jwt_token_here"),
     *                 @OA\Property(property="type", type="string", example="bearer"),
     *                 @OA\Property(property="expires_in", type="integer", example=3600)
     *             ),
     *             @OA\Property(property="message", type="string", example="token successfully refreshed")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized, invalid or expired token",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Unauthorized")
     *         )
     *     )
     * )
     */
    public function refresh()
    {
        $newToken = Auth::refresh();
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
            'authorization' => [
                'token' => $newToken,
                'type' => 'bearer',
                'expires_in' => Auth::factory()->getTTL() * 60
            ],
            'message' => 'token successfully refreshed',
        ]);
    }
}
