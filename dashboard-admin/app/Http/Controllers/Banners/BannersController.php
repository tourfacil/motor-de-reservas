<?php namespace App\Http\Controllers\Banners;

use App\Http\Requests\Banners\ChangeBannerDestinoRequest;
use App\Http\Requests\Banners\StoreBannerDestinoRequest;
use App\Http\Requests\Banners\UpdateBannerDestinoRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use TourFacil\Core\Enum\BannerEnum;
use TourFacil\Core\Models\BannerDestino;
use TourFacil\Core\Models\Destino;
use TourFacil\Core\Services\UploadPhotoService;

/**
 * Class BannersController
 * @package App\Http\Controllers\Banners
 */
class BannersController extends Controller
{
    /**
     * Listagem dos banners por canal de venda e destino
     *
     * @param null $destino_id
     * @return mixed
     */
    public function index($destino_id = null)
    {
        // Canal de venda na sessao do usuario
        $canal_venda = canalSession()->getCanal();

        // Recupera todos os destinos do canal de venda
        $destinos = Destino::whereHas('servicosAtivos')
            ->where('canal_venda_id', $canal_venda->id)
            ->oldest()->get();

        // Id do destino selecionado na URL
        $destino_id = $destino_id ?? $destinos->first()->id ?? null;

        // Destino selecionado
        $selecionado = $destinos->first(function ($destino) use ($destino_id) {
            return ($destino->id == $destino_id);
        });

        // Caso nao encontre o destino selecionado
        if(is_null($selecionado)) return redirect()->route('app.dashboard');

        // Banners do destino
        $banners = BannerDestino::withTrashed()
            ->where('destino_id', $destino_id)
            ->orderBy('ordem')->get();

        return view('paginas.banners.banners', compact(
            'canal_venda',
            'destinos',
            'selecionado',
            'banners'
        ));
    }

    /**
     * View para cadastro do novo banner
     *
     * @param null $destino_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create($destino_id = null)
    {
        // Canal de venda na sessao do usuario
        $canal_venda = canalSession()->getCanal();

        // Recupera todos os destinos do canal de venda
        $destinos = Destino::whereHas('servicosAtivos')
            ->where('canal_venda_id', $canal_venda->id)
            ->oldest()->get();

        // Id do destino selecionado na URL
        $destino_id = $destino_id ?? $destinos->first()->id;

        // Destino selecionado
        $selecionado = $destinos->first(function ($destino) use ($destino_id) {
            return ($destino->id == $destino_id);
        });

        // Tipos de banners
        $tipo_banners = BannerEnum::TIPOS;
        $tipo_servico = BannerEnum::TIPO_SERVICO;

        return view('paginas.banners.novo-banner', compact(
            'canal_venda',
            'destinos',
            'selecionado',
            'tipo_banners',
            'tipo_servico'
        ));
    }

    /**
     * Detalhes do banner
     *
     * @param $banner_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function view($banner_id)
    {
        $banner = BannerDestino::with([
            'destino.canalVenda',
            'destino' => function($q) {
                return $q->select(['id', 'nome', 'canal_venda_id']);
            },
            'servico' => function($q) {
                return $q->select(['id', 'nome']);
            },
        ])->withTrashed()->find($banner_id);

        return view('paginas.banners.detalhes-banner', compact(
            'banner'
        ));
    }

    /**
     * POST para cadastro de um novo banner
     *
     * @param StoreBannerDestinoRequest $request
     * @return array
     */
    public function store(StoreBannerDestinoRequest $request)
    {
        $dados = $request->all();

        // Canal de venda na sessao do usuario
        $canal_venda = canalSession()->getCanal();

        // Faz o upload do banner
        $photo = UploadPhotoService::uploadBannerDestino($request->file('banner'), $dados['titulo'], $canal_venda);

        // Caso faça o upload
        if($photo['upload']) {

            // Salva o caminho do banner
            $dados['banner'] = $photo['foto'];

            // Cria um novo banner com os dados do request
            $banner = BannerDestino::create($dados);

            if(is_object($banner)) {
                return $this->autoResponseJson(
                    true, "Banner cadastrado",
                    "O banner foi cadastrado com sucesso!",
                    route('app.banners.index', $banner->destino_id)
                );
            }
        }

        return $this->autoResponseJson(false, "Banner não cadastrado", "Não foi possível cadastrar o banner, tente novamente!");
    }

    /**
     * Atualiza as informações do banner
     *
     * @param UpdateBannerDestinoRequest $request
     * @return array
     */
    public function update(UpdateBannerDestinoRequest $request)
    {
        // Recupera o banner para ser atualizado
        $banner = BannerDestino::with('destino.canalVenda')
            ->withTrashed()->find($request->get('banner_id'));

        // Caso encontre o banner
        if(is_object($banner)) {

            // Caso esteja atualizando o banner
            if($request->hasFile('banner')) {
                // Faz o upload do banner
                $photo = UploadPhotoService::uploadBannerDestino($request->file('banner'), $request->get('titulo'), $banner->destino->canalVenda);
            }

            // Atualiza os dados do banner
            $update = $banner->update([
                'ordem' => $request->get('ordem'),
                'titulo' => $request->get('titulo'),
                'descricao' => $request->get('descricao'),
                'banner' => $photo['foto'] ?? $banner->banner
            ]);

            if($update) {
                return $this->autoResponseJson(true, "Banner atualizado", "O banner foi atualizado com sucesso!");
            }
        }

        return $this->autoResponseJson(false, "Banner não atualizado", "Não foi possível atualizar o banner, tente novamente!");
    }

    /**
     * PUT para alterar o status do banner
     * DESATIVAR, REATIVAR E EXCLUIR
     *
     * @param ChangeBannerDestinoRequest $request
     * @return array
     */
    public function status(ChangeBannerDestinoRequest $request)
    {
        // Recupera a acao
        $action = $request->get('action');

        // Recupera o banner para ser atualizado
        $banner = BannerDestino::withTrashed()->find($request->get('banner_id'));

        // Caso seja para desativar
        if($action == 'desativar') {
            if($banner->delete())
                return $this->autoResponseJson(true, "Banner desativado", "O banner foi desativado com sucesso!");
            return $this->autoResponseJson(false, "Erro ao desativar", "O banner não foi desativado, tente novamente!");
        }

        // Caso seja para reativar
        if($action == 'ativar') {
            if($banner->restore())
                return $this->autoResponseJson(true, "Banner reativado", "O banner foi reativado com sucesso!");
            return $this->autoResponseJson(false, "Erro ao reativar", "O banner não foi reativado, tente novamente!");
        }

        // Caso seja para excluir o banner
        if($action == 'excluir') {
            $destino_id = $banner->destino_id;
            if($banner->forceDelete())
                return $this->autoResponseJson(true, "Banner excluído", "O banner foi excluído com sucesso!", route('app.banners.index', $destino_id));
            return $this->autoResponseJson(false, "Erro ao excluir", "O banner não foi excluído, tente novamente!");
        }

        return $this->autoResponseJson(false, "Erro ao processar", "Não foi encontrada nenhuma ação, tente novamente!!");
    }
}
