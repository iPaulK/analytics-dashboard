<?php

namespace App\Http\Middleware;

use App\Exceptions\UnauthorizedException;
use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;
use WoohooLabs\Yin\JsonApi\JsonApi;

class RoleMiddleware
{
    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string $roles
     * @return mixed
     */
    public function handle($request, Closure $next, $roles)
    {
        if ($this->auth->guard()->guest() || !$request->user()->hasRole(explode('|', $roles))) {
            throw new UnauthorizedException("User does not have the right roles");
        }

        return $next($request);
    }
}
