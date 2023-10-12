<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

use function Psy\debug;

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
        $pattern = '/jwt=([^;]+)/';
        $cookie = $request->headers->get('cookie') ?? "";

        if (preg_match($pattern, $cookie, $matches)) {
            $request->headers->set('Authorization', 'Bearer ' . $matches[1]);
        }

        $this->authenticate($request, $guards);

        return $next($request);
    }
}
