<?php namespace App\Http\Controllers\Painel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use TourFacil\Core\Services\FornecedorService;

/**
 * Class ValidadorController
 * @package App\Http\Controllers\Painel
 */
class ValidadorController extends Controller
{
    /**
     * Validador de reservas
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        // Fornecedor id do usuario
        $fornecedor_id = auth()->user()->fornecedor_id;

        // Recupera as reservas do fornecedor
        $reservas = FornecedorService::reservasAtivasFornecedor($fornecedor_id);

        return view('paginas.validador', compact(
            'reservas'
        ));
    }
}
