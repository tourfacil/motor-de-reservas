<?php namespace App\Http\Controllers\Newsletter;

use App\Exports\Terminais\RelatorioVendasTerminaisExport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use TourFacil\Core\Models\Newsletter;

/**
 * Class NewsletterController
 * @package App\Http\Controllers\Newsletter
 */
class NewsletterController extends Controller
{
    /**
     * Listagem das newsletters do site
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        // Canal de venda na sessao
        $canal_venda = canalSession()->getCanal();

        // Recupera os email do canal de venda
        $newsletters = Newsletter::where('canal_venda_id', $canal_venda->id)->limit(500)->latest()->get();

        return view('paginas.newsletters.newsletters', compact(
            'newsletters',
            'canal_venda'
        ));
    }

    /**
     * Download da lista de newsletter
     *
     * @return \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function download()
    {
        // Canal de venda na sessao do usuario
        $canal_venda = canalSession()->getCanal();

        // Recupera os email do canal de venda
        $newsletters = Newsletter::where('canal_venda_id', $canal_venda->id)->latest()->get();

        // Variaveis para download
        $variaveis = compact(
            'newsletters',
            'canal_venda'
        );

        return (new RelatorioVendasTerminaisExport('paginas.newsletters.newsletter-xls', $variaveis))
            ->download("Newsletters {$canal_venda->nome}.xlsx");
    }
}
