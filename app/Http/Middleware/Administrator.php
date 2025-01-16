<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Administrator
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return response()->json([
                'message' => 'Unauthorized, user not authenticated.',
                'success' => false,
                'status' => 401
            ], 401);
        } elseif (Auth::check()) {
            $user = Auth::user();
            if ($user->roles !== 'admin') {
                // dd("pas connecté en tant que admin");
                return response()->json([
                    'message' => 'Forbidden, user is not an admin.',
                    'success' => false,
                    'status' => 403,
                ], 403);
            }
            return $next($request);
        }
    }
}
