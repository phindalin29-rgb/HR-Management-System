<?php

namespace App\Http\Middleware;

use App\Models\ApiToken;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateApi
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $hash = hash('sha256', $token);

        $apiToken = ApiToken::where('token', $hash)->first();

        if (!$apiToken || $apiToken->isExpired()) {
            return response()->json(['message' => 'Invalid or expired token.'], 401);
        }

        $user = User::where('user_id', $apiToken->user_id)->first();

        if (!$user || $user->status !== 'Active') {
            return response()->json(['message' => 'Account is not active.'], 403);
        }

        $apiToken->last_used_at = now();
        $apiToken->save();

        Auth::setUser($user);
        $request->setUserResolver(fn () => $user);

        return $next($request);
    }
}
