<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Session;
use Symfony\Component\HttpFoundation\Response;

/**
 * Ensures the authenticated user is allowed to access the given module(s).
 * Users without an assigned role are treated as unrestricted (legacy-safe).
 */
class CheckModule
{
    public function handle(Request $request, Closure $next, string ...$modules): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(403, 'Unauthenticated.');
        }

        if (empty($modules)) {
            return $next($request);
        }

        $sessionModules = Session::get('modules');

        $allowed = false;

        if (! empty($sessionModules)) {
            $allowed = in_array('*', $sessionModules, true)
                || count(array_intersect($modules, $sessionModules)) > 0;
        } else {
            $allowed = $user->hasAnyModule($modules);
        }

        if (! $allowed) {
            abort(403, 'អ្នកគ្មានសិទ្ធិចូលប្រើមុខងារនេះ។');
        }

        return $next($request);
    }
}
