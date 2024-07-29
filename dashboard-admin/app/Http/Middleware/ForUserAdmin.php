<?php namespace App\Http\Middleware;

use Auth;
use Closure;

class ForUserAdmin
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
        if (userIsAdmin() === false) {
            return redirect()->route('app.dashboard')->with(
                'status', "Você não possui permissão para acessar essa página!"
            );
        }

        return $next($request);
    }
}
