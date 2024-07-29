<?php namespace App\Http\Controllers\Painel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use TourFacil\Core\Enum\ServicoEnum;
use TourFacil\Core\Models\Servico;
use TourFacil\Core\Models\Terminal;
use TourFacil\Core\Services\Cache\CategoriaCacheService;
use TourFacil\Core\Services\Cache\DefaultCacheService;
use TourFacil\Core\Services\FornecedorService;

/**
 * Class ServicosController
 * @package App\Http\Controllers\Painel
 */
class ServicosController extends Controller
{
    /**
     * Listagem dos servicos do parceiro
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        // Fornecedor id do usuario
        $fornecedor_id = auth()->user()->fornecedor_id;

        // Recupera os servicos ativos para o terminal
        $servicos = FornecedorService::getServicosFornecedor($fornecedor_id);

        return view('paginas.servicos.servicos', compact(
            'servicos'
        ));
    }
}
