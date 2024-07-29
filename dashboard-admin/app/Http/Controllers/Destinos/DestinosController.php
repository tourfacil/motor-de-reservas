<?php namespace App\Http\Controllers\Destinos;

use App\Http\Requests\StoreDestinoRequest;
use App\Http\Requests\UpdateDestinoRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use TourFacil\Core\Enum\FotoServicoEnum;
use TourFacil\Core\Enum\ServicoEnum;
use TourFacil\Core\Enum\TipoHomeDestinoEnum;
use TourFacil\Core\Models\CanalVenda;
use TourFacil\Core\Models\Destino;
use TourFacil\Core\Models\Servico;
use TourFacil\Core\Services\DestinoService;
use TourFacil\Core\Services\UploadPhotoService;

/**
 * Class DestinosController
 * @package App\Http\Controllers\Destinos
 */
class DestinosController extends Controller
{
    /**
     * Página de listagem dos destinos
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $canal_venda = canalSession()->getCanal();

        $destinos = DestinoService::destinoCanalVenda($canal_venda, true);

        return view('paginas.destinos.destinos', compact(
            'destinos'
        ));
    }

    /**
     * View para cadastro do destino
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $canais_venda = CanalVenda::all();

        $canal_user = canalSession()->getCanal();

        $dimensao_foto = Destino::$PHOTO_PRESET[FotoServicoEnum::LARGE];

        return view('paginas.destinos.novo-destino', compact(
            'canais_venda',
            'canal_user',
            'dimensao_foto'
        ));
    }

    /**
     * View para detalhes do destino
     *
     * @param $destino_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function view($destino_id)
    {
        $destino = Destino::with([
            'canalVenda',
            'homeDestino' => function($q) {
                return $q->withTrashed()->with([
                    'servicos' => function($f) {
                        return $f->selectRaw('COUNT(servicos.id) as quantidade')
                            ->groupBy('home_destino_servico.home_destino_id');
                    }
                ]);
            }
        ])->withTrashed()->findOrFail($destino_id);

        $dimensao_foto = Destino::$PHOTO_PRESET[FotoServicoEnum::LARGE];

        $tipos_secao = TipoHomeDestinoEnum::TIPOS_HOME_DESTINO;

        // Recupera os servicos do destino
        $servicos = Servico::with([
            'fornecedor' => function($q) { return $q->select(['id', 'nome_fantasia']);}
        ])->where([
            'destino_id' => $destino->id,
            'status' => ServicoEnum::ATIVO
        ])->select(['id', 'fornecedor_id', 'nome'])->orderBy('nome')->get();

        return view('paginas.destinos.detalhes-destino', compact(
            'destino', 'dimensao_foto', 'tipos_secao', 'servicos'
        ));
    }

    /**
     * Detalhes do destino com categorias em JSON
     *
     * @param $destino_id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]
     */
    public function viewJson($destino_id)
    {
        return Destino::with('categorias')->findOrFail($destino_id);
    }

    /**
     * Post para cadastro do destino
     *
     * @param StoreDestinoRequest $request
     * @return array
     */
    public function store(StoreDestinoRequest $request)
    {
        $destino = Destino::create($request->all());

        if(is_object($destino)) {
            return [
                'action' => true,
                'destino' => $destino,
                'view' => route('app.destinos.view', $destino->id),
            ];
        }

        return ['action' => false];
    }

    /**
     * PUT para editar dados do destino
     *
     * @param UpdateDestinoRequest $request
     * @return array
     */
    public function update(UpdateDestinoRequest $request)
    {
        // Procura o destino
        $destino = Destino::find($request->get('destino_id'));

        // Verifica se encontrou o destino
        if(is_object($destino)) {

            // Atualiza os dados
            $destino->update($request->all());

            return $this->autoResponseJson(true, "Destino atualizado", "As informações do destino foram salvas com sucesso!");
        }

        return $this->autoResponseJson(false, "Destino não atualizado", "Não foi possível atualizar os dados do destino, tente novamente!");
    }

    /**
     * Envia a foto do destino
     *
     * @param Request $request
     * @return array
     */
    public function uploadPhotoDestino(Request $request)
    {
        // Foto do destino
        $photo = $request->file('foto');

        // Recupera o destino
        $destino = Destino::with('canalVenda')->find($request->get('destino_id'));

        // Encontra o destino
        if(is_object($destino)) {

            // Envia a foto para a Amazon
            $image = UploadPhotoService::uploadPhotoDestino($photo, $destino, $destino->canalVenda);

            // Verifica se fez o upload da foto
            if($image['upload']) {

                // Salva a foto do destino
                $destino->update(['foto' => $image['fotos']]);

                return ['action' => true];
            }

            return ['action' => false, 'message' => 'Não foi possível fazer o upload da foto'];
        }

        return ['action' => false, 'message' => 'Destino não encontrado'];
    }

    /**
     * Retorna a lista do servicos para um destino
     *
     * @param null $destino_id
     * @return mixed
     */
    public function servicosJson($destino_id = null)
    {
        return Servico::where('destino_id', $destino_id)
            ->where('status', ServicoEnum::ATIVO)->get(['id', 'nome']);
    }
}
