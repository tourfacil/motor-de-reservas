<?php

namespace App\Http\Middleware;

use Closure;
use TourFacil\Core\Models\Afiliado;
use TourFacil\Core\Models\Vendedor;

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

        // Busca os ID's de afiliados
        $afiliado_id_url = $request->get('aid');
        $vendedor_id_url = $request->get('sid');

        // Caso não tenha afiliado na URL ele retorna o site
        if($afiliado_id_url == null && $vendedor_id_url == null) {
            return $next($request);
        }

        // Para testes ou uso do operacional, caso seja informado 999. O afiliado é removido
        if($afiliado_id_url == '999') {
            session()->forget('afiliado');
            session()->save();

            return $next($request);
        }

        // Para testes ou uso do operacional, caso seja informado 999. O afiliado é removido
        if($vendedor_id_url == '999') {
            session()->forget('vendedor');
            session()->save();

            return $next($request);
        }

        // Busca o afiliado no banco de dados
        $afiliado_db = Afiliado::find($afiliado_id_url);

        // Busca o vendedor
        $vendedor_db = Vendedor::find($vendedor_id_url);

        // Afiliado setado na sessão caso necessario
        if($afiliado_db != null) {
            session(['afiliado' => $afiliado_db]);
            session()->save();
        }

        // Vendedor setado na sessão caso necessario
        if($vendedor_db != null) {
            session(['vendedor' => $vendedor_db]);
            session()->save();
        }

        return $next($request);
    }
}
