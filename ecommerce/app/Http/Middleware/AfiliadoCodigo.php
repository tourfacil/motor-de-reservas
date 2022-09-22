<?php

namespace App\Http\Middleware;

use Closure;
use TourFacil\Core\Models\Afiliado;

class AfiliadoCodigo
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

        $afiliado_id_url = $request->get('aid');

        // Caso não tenha afiliado na URL ele retorna o site
        if($afiliado_id_url == null) {
            return $next($request);
        }

        // Para testes ou uso do operacional, caso seja informado 999. O afiliado é removido
        if($afiliado_id_url == '999') {
            session()->forget('afiliado');
            session()->save();

            return $next($request);
        }

        // Busca o afiliado no banco de dados
        $afiliado_db = Afiliado::find($afiliado_id_url);
        
        // Caso o afiliado não exista... Ele retorna para o site.
        if($afiliado_db == null) {
            return $next($request);
        }
        
        // Caso o afiliado realmente exista ele seta na sessão
        session(['afiliado' => $afiliado_db]);
        session()->save();

        // Retorna para o site agora com o afiliado na sessão

        return $next($request);
    }
}
