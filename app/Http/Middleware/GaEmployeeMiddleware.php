<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Exceptions\UnauthorizedException;

class GaEmployeeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $route = $request->route();
        $accountId = $route[2]['accountId'];

        $currentUser = JWTAuth::user();
        if (!$currentUser->role->isEmployee()) {
            return $next($request);
        }

        $accountIds = $currentUser->getAvailableAccounts();
        if (!in_array($accountId, $accountIds)) {
            throw new UnauthorizedException("User does not have the right roles");
        }

        return $next($request);
    }
}
