<?php

namespace App\Http\Controllers;

use App\Http\Requests\CanalVendaRequest;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use TourFacil\Core\Enum\CanaisVendaEnum;
use TourFacil\Core\Models\CanalVenda;
use TourFacil\Core\Services\CanalVendaService;

/**
 * Class CanalVendaController
 * @package App\Http\Controllers
 */
class CanalVendaController extends Controller
{
    /**
     * Canais de venda
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $canais_venda = CanalVenda::withTrashed()->orderBy('created_at', 'DESC')->get();

        return view('paginas.canal-venda.canais-de-venda', compact(
            'canais_venda'
        ));
    }

    /**
     * View para cadastro de novo canal
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('paginas.canal-venda.novo-canal-venda');
    }

    /**
     * Cadastra um novo canal de venda
     *
     * @param CanalVendaRequest $request
     * @return array
     */
    public function store(CanalVendaRequest $request)
    {
        $novo_canal = CanalVenda::create($request->all());

        // Limpa o cache dos canais de vendas
        CanalVendaService::flushCache();

        if (is_object($novo_canal)) {
            return $this->autoResponseJson(
                true,
                "Canal cadastrado",
                "O canal {$novo_canal->nome} foi cadastrado com sucesso!",
                route('app.canal-venda.view', $novo_canal->id)
            );
        }

        return $this->autoResponseJson(false, "Canal não cadastrado", "Não foi possível cadastrar o canal, tente novamente!");
    }

    /**
     * Detalhes do canal de venda
     *
     * @param $canal_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function view($canal_id)
    {
        // Encontra o canal de venda
        $canal_venda = CanalVenda::withTrashed()->findOrFail($canal_id);

        $path_reset_cache = CanaisVendaEnum::URL_CACHE_CLEAR;

        return view('paginas.canal-venda.detalhes-canal-venda', compact(
            'canal_venda',
            'path_reset_cache'
        ));
    }

    /**
     * Atualiza os dados do canal
     *
     * @param CanalVendaRequest $request
     * @return array
     */
    public function edit(CanalVendaRequest $request)
    {
        // Encontra o canal de venda
        $canal_venda = CanalVenda::withTrashed()->find($request->get('canal_venda_id'));

        if (is_object($canal_venda)) {

            // Atualiza os dados do canal
            $canal_venda->update($request->all());

            // Limpa o cache dos canais de vendas
            CanalVendaService::flushCache();

            // Caso seja para desativar o canal
            if ($request->get('disable') == null) {
                $canal_venda->delete();
            } else {
                $canal_venda->restore();
            }

            return $this->autoResponseJson(true, "Canal atualizado", "O canal {$canal_venda->nome} foi atualizado com sucesso!");
        }

        return $this->autoResponseJson(false, "Canal não atualizado", "Não foi possível atualizar o canal, tente novamente!");
    }

    /**
     * Post para limpar o cache do canal de venda
     *
     * @param Request $request
     * @return array
     */
    public function resetCache(Request $request)
    {
        // Valida a requisicao
        $request->validate(['canal_venda_id' => 'required|integer']);

        // Encontra o canal de venda
        $canal_venda = CanalVenda::withTrashed()->find($request->get('canal_venda_id'));

        // Monta a URL de limpar o cache
        $url_reset_cache = $canal_venda->site . CanaisVendaEnum::URL_CACHE_CLEAR;

        // Efetua um GET na URL para limpar o cache
        $res = (new Client(['verify' => false]))->get($url_reset_cache);

        return [
            'status_code' => $res->getStatusCode(),
            'response' => json_decode($res->getBody()->getContents()),
        ];
    }
}
