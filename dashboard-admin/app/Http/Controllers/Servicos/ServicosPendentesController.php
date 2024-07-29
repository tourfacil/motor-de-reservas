<?php namespace App\Http\Controllers\Servicos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use TourFacil\Core\Enum\ServicoEnum;
use TourFacil\Core\Models\Servico;

/**
 * Class ServicosPendentesController
 * @package App\Http\Controllers\Servicos
 */
class ServicosPendentesController extends Controller
{
    /**
     * Listagem de TODOS os servicos pendentes
     * Sem filtro por canal de venda
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        // TODOS Servicos pendentes
        $servicos = Servico::with('fornecedor', 'canalVenda')
            ->where("status", ServicoEnum::PENDENTE)
            ->orderBy('created_at', 'ASC')->get();

        return view('paginas.servicos.servicos-pendentes', compact(
            'servicos'
        ));
    }
}
