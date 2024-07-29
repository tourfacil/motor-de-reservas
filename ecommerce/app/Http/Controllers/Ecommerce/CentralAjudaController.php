<?php namespace App\Http\Controllers\Ecommerce;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * Class CentralAjudaController
 * @package App\Http\Controllers\Ecommerce
 */
class CentralAjudaController extends Controller
{
    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        // Destino salvo na sessao
        $destino_atual = $this->getDestinoSession();

        // URL para a logo na navbar
        $url_logo = $destino_atual['url_destino'] ?? route('ecommerce.index');

        return redirect()->to($url_logo);
    }

    /**
     * Página sobre a empresa
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function sobreEmpresa()
    {
        // Destino salvo na sessao
        $destino_atual = $this->getDestinoSession();

        // URL para a logo na navbar
        $url_logo = $destino_atual['url_destino'] ?? route('ecommerce.index');

        return view('paginas.ajuda.sobre-empresa', compact(
            'url_logo'
        ));
    }

    /**
     * Termos e condicoes
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function termos()
    {
        // Destino salvo na sessao
        $destino_atual = $this->getDestinoSession();

        // URL para a logo na navbar
        $url_logo = $destino_atual['url_destino'] ?? route('ecommerce.index');

        return view('paginas.ajuda.termos', compact(
            'url_logo'
        ));
    }

    /**
     * Página sobre a politica de privacidade
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function politicaPrivacidade()
    {
        // Destino salvo na sessao
        $destino_atual = $this->getDestinoSession();

        // URL para a logo na navbar
        $url_logo = $destino_atual['url_destino'] ?? route('ecommerce.index');

        return view('paginas.ajuda.privacidade', compact(
            'url_logo'
        ));
    }

    /**
     * Página sobre a politica de cancelamento
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function politicaCancelamento()
    {
        // Destino salvo na sessao
        $destino_atual = $this->getDestinoSession();

        // URL para a logo na navbar
        $url_logo = $destino_atual['url_destino'] ?? route('ecommerce.index');

        return view('paginas.ajuda.politica-cancelamento', compact(
            'url_logo'
        ));
    }
}
