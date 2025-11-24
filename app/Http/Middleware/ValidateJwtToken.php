<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class ValidateJwtToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            // Coba untuk authenticate user dari token
            $user = JWTAuth::parseToken()->authenticate();
            if (!$user) {
                return $this->redirectToLogin($request, 'User not found');
            }
            
        } catch (TokenExpiredException $e) {
            // Token sudah expired
            return $this->redirectToLogin($request, 'Token has expired');
            
        } catch (TokenInvalidException $e) {
            // Token tidak valid
            return $this->redirectToLogin($request, 'Token is invalid');
        } catch (JWTException $e) {
            // Token tidak ada di header
            return $this->redirectToLogin($request, 'Token not provided');
        }

        return $next($request);
    }

    /**
     * Redirect to login page atau return JSON response
     */
    private function redirectToLogin(Request $request, string $message): Response
    {
        // Jika request mengharapkan JSON (API request)
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => $message,
                'redirect' => '/login'
            ], 401);
        }

        // Jika request dari web browser, redirect ke halaman login
        return redirect('/login')->with('error', $message);
    }
}
