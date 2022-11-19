<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JWTMiddleware extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(Request): (Response|RedirectResponse) $next
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            $jwt = JWTAuth::parseToken()->authenticate();
        } catch (JWTException $e) {
            $jwt = false;
        }
        if (Auth::check() || $jwt) {
            return $next($request);
        } else {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

    }
}
