<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;

class Authenticate extends Middleware
{
   
    protected function redirectTo($request)
    {
       //  Auth::loginUsingId(1);
        if (! $request->expectsJson()) {
            return route('login');
        }
    }
   /* 
    public function handle($request, Closure $next, ...$guards)
{
    if (env('DISABLE_AUTH', false)) {
        return $next($request); // Skip authentication if disabled
    }

    $this->authenticate($request, $guards);

    return $next($request);
}*/

}

/*
namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Closure;

class Authenticate extends Middleware
{

    public function handle($request, Closure $next, ...$guards)
    {
        if (env('DISABLE_AUTH', false)) {
            return $next($request); // Skip authentication if disabled
        }

        $this->authenticate($request, $guards);

        return $next($request);
    }
}
*/