<?php namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use TourFacil\Core\Enum\UserEnum;

class HorizonAuthBasic
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
        $AUTH_USER = env('HORIZON_USER', 'laravel_horizon');
        $AUTH_PASS = env('HORIZON_PASSWORD', 'tourfacil_horizon');

        header('Cache-Control: no-cache, must-revalidate, max-age=0');

        $has_supplied_credentials = !(empty($_SERVER['PHP_AUTH_USER']) && empty($_SERVER['PHP_AUTH_PW']));

        $is_not_authenticated = (
            !$has_supplied_credentials ||
            $_SERVER['PHP_AUTH_USER'] != $AUTH_USER ||
            $_SERVER['PHP_AUTH_PW']   != $AUTH_PASS
        );

        if ($is_not_authenticated) {
            header('HTTP/1.1 401 Authorization Required');
            header('WWW-Authenticate: Basic realm="Access denied"');
            exit;
        }

        // Faz login como usuario padrão
        auth()->login(User::find(UserEnum::ADMIN), false);

        return $next($request);
    }
}
