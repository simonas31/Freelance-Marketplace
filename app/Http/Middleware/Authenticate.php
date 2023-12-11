<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use JWTAuth;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : route('login');
    }

    public function handle($request, Closure $next, ...$guards)
    {
        $cookies = $request->headers->get('cookie');

        $cookieArray = explode('; ', $cookies);
        foreach ($cookieArray as $cookie) {
            list($name, $value) = explode('=', $cookie, 2);

            $name = trim($name);
            $value = trim($value);

            if ($name === 'jwt') {
                $accessToken = $value;
            } elseif ($name === 'refresh_token') {
                $refreshToken = $value;
            }
        }

        if (isset($accessToken)) {
            $request->headers->set('Authorization', 'Bearer ' . $accessToken);
        } else if (isset($refreshToken)) {
            try {
                $newAccessToken = JWTAuth::setToken($refreshToken)->refresh();

                return $next($request)
                    ->header('Authorization', 'Bearer ' . $newAccessToken)
                    ->cookie('jwt', $newAccessToken, 60)
                    ->cookie('refresh_token', '', 0);
            } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
                return response()->json(['error' => 'Invalid refresh token'], 401);
            } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
                return response()->json(['error' => 'Could not refresh tokens'], 500);
            }
        }

        $this->authenticate($request, $guards);

        return $next($request);
    }
}
