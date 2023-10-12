<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $pattern = '/jwt=([^;]+)/';
        $cookie = $request->headers->get('cookie') ?? "";

        if (preg_match($pattern, $cookie, $matches)) {
            $request->headers->set('Authorization', 'Bearer ' . $matches[1]);
        }

        return $next($request);
    }
}
