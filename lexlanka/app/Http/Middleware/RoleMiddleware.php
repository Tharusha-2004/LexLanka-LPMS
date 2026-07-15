<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Accepts a comma-separated list of allowed roles passed as middleware
     * parameters, e.g. middleware('role:partner,associate').
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles  One or more allowed roles
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // 1. Must be authenticated
        if (! $request->user()) {
            abort(403, 'Unauthorized');
        }

        // 2. Role must be in the allowed list
        if (! in_array($request->user()->role, $roles)) {
            abort(403, 'You do not have permission to access this resource.');
        }

        return $next($request);
    }
}
