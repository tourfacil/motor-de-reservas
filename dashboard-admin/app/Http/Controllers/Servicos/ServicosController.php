<?php namespace App\Http\Controllers\Servicos;

use App\Http\Requests\StoreCategoriaServicoRequest;
use App\Http\Requests\StoreServicoRequest;
use App\Http\Requests\UpdateCategoriaServicoRequest;
use App\Http\Requests\UpdateServicoRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use TourFacil\Core\Enum\CampoAdicionalEnum;
use TourFacil\Core\Enum\CategoriasEnum;
use TourFacil\Core\Enum\FotoServicoEnum;
use TourFacil\Core\Enum\IntegracaoEnum;
use TourFacil\Core\Enum\ServicoEnum;
use TourFacil\Core\Enum\VariacaoServicoEnum;
use TourFacil\Core\Models\Categoria;
use TourFacil\Core\Models\Fornecedor;
use TourFacil\Core\Models\Servico;
use TourFacil\Core\Services\DestinoService;
use TourFacil\Core\Services\RegraServico\ValorExcecaoDiaService;
use TourFacil\Core\Services\ServicoService;
use TourFacil\Core\Services\AgendaService;

/**
 * Class ServicosController
 * @package App\Http\Controllers\Servicos
 */
class ServicosController extends Controller
{
    /**
     * Listagem dos serviços
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        // Canal de venda na sessao do usuario
        $canal_venda = canalSession()->getCanal();

        // Servicos
        $servicos = Servico::with([
            'destino',
            'fornecedor' => function($q) {
                return $q->select(['id', 'nome_fantasia']);
            }
        ])->where('canal_venda_id', $canal_venda->id)
            ->orderBy('nome', 'ASC')->get([
                'id', 'fornecedor_id', 'destino_id', 'nome', 'valor_venda', 'status'
            ]);

        return view('paginas.servicos.servicos', compact(
            'canal_venda',
            'servicos'
        ));
    }

    /**
     * View para cadastro do serviço
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        // Canal de venda na sessao do usuario
        $canal_venda = canalSession()->getCanal();

        // Fornecedores
        $fornecedores = Fornecedor::select(['id', 'nome_fantasia'])->orderBy('nome_fantasia')->get();

        // Destinos do canal
        $destinos = DestinoService::destinosHasCategorias($canal_venda);

        // Antecedencia default
        $antecedencia_default = ServicoEnum::ANTECEDENCIA_DEFAULT;

        // Informacoes dos clientes
        $info_clientes = ServicoEnum::INFO_CLIENTES;

        // Corretagem serviço
        $corretagens = ServicoEnum::TIPOS_CORRETAGEM;

        // Opções campos adicionais
        $op_campos_adicionais = CampoAdicionalEnum::OPCOES;

        // Preset da foto do serviço
        $preset_foto = Servico::$PHOTO_PRESET[FotoServicoEnum::LARGE];

        // Variacao consome bloqueio
        $consome_bloqueio = VariacaoServicoEnum::CONSOME_BLOQUEIO;

        // Variacao NAO consome bloqueio
        $nao_consome_bloqueio = VariacaoServicoEnum::NAO_CONSOME_BLOQUEIO;

        // Enum da categoria padrao
        $e_categoria_padrao = CategoriasEnum::CATEGORIA_PADRAO;

        // Tipo da categoria no servico
        $e_tipo_categoria = CategoriasEnum::CATEGORIA_PADRAO_SERVICO[$e_categoria_padrao];

        // Status de pendente
        $status_pendente = ServicoEnum::STATUS_SERVICO[ServicoEnum::PENDENTE];

        // Integracoes disponiveis
        $integracoes = IntegracaoEnum::INTEGRACOES;

        // tipos variacao
        $tipos_variacao = VariacaoServicoEnum::TIPO_VARIACAO;

        return view('paginas.servicos.novo-servico', compact(
            'canal_venda',
            'fornecedores',
            'destinos',
            'antecedencia_default',
            'info_clientes',
            'corretagens',
            'op_campos_adicionais',
            'preset_foto',
            'consome_bloqueio',
            'nao_consome_bloqueio',
            'status_pendente',
            'integracoes',
            'tipos_variacao',
            'e_categoria_padrao',
            'e_tipo_categoria'
        ));
    }

    /**
     * View para detalhes do serviço
     *
     * @param $servico_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function view($servico_id)
    {
        // Serviço
        $servico = Servico::with([
            'tags',
            'tagsInternas',
            'canalVenda',
            'fornecedor',
            'destino',
            'camposAdicionais',
            'fotos',
            'categorias',
            'secoesCategoria',
            'variacaoServico',
            'agendaServico',
        ])->findOrFail($servico_id);

        $regra_antecedencia = ValorExcecaoDiaService::getRegraAtecedenciaServicoAtiva($servico);

        // Verifica as informacoes do servico
        $info_servico = ServicoService::verificarInfoServico($servico);

        // Informacoes dos clientes
        $info_clientes = ServicoEnum::INFO_CLIENTES;

        // Corretagem serviço
        $corretagens = ServicoEnum::TIPOS_CORRETAGEM;

        // Corretagem em percentual
        $c_percentual = ServicoEnum::CORRETAGEM_PORCENTUAL;

        // Opções campos adicionais
        $op_campos_adicionais = CampoAdicionalEnum::OPCOES;

        // Preset da foto do serviço
        $preset_foto = Servico::$PHOTO_PRESET[FotoServicoEnum::LARGE];

        // Enum para foto principal
        $e_principal = FotoServicoEnum::PRINCIPAL;

        // Status ativo
        $s_ativo = ServicoEnum::ATIVO;

        // Status Inativo
        $s_inativo = ServicoEnum::INATIVO;

        // Status Indisponivel
        $s_indisponivel = ServicoEnum::INDISPONIVEL;

        // Status pendente
        $s_pendente = ServicoEnum::PENDENTE;

        // Enum para categoria padrao do servico
        $e_categoria_servico = CategoriasEnum::CATEGORIA_PADRAO_SERVICO;

        // Variacao consome bloqueio
        $consome_bloqueio = VariacaoServicoEnum::CONSOME_BLOQUEIO;

        // Variacao NAO consome bloqueio
        $nao_consome_bloqueio = VariacaoServicoEnum::NAO_CONSOME_BLOQUEIO;

        // Status de publicacao do servico
        $status_servico = array_except(ServicoEnum::STATUS_SERVICO, ServicoEnum::PENDENTE);

        // Integracoes disponiveis
        $integracoes = IntegracaoEnum::INTEGRACOES;

        // tipos variacao
        $tipos_variacao = VariacaoServicoEnum::TIPO_VARIACAO;

        // Enum de categoria padrao
        $e_categoria_padrao = CategoriasEnum::CATEGORIA_PADRAO;

        // Será usado no try catch
        $url_online = "";

        // Try Catch para evitar que produtos sem categoria buguem a página
        try {
            // Monta a URL online do servico
            $url_online = "{$servico->canalVenda->site}/{$servico->destino->slug}/{$servico->categoria->slug}/{$servico->slug}";

        } catch(\Exception $exception) {

        }

        // icones tags
        $icones_jam = file_get_contents(resource_path("assets/fonts/jam-icons.json"));
        $icones_jam = ($icones_jam) ? json_decode($icones_jam, true)["icons"] : [];



        return view('paginas.servicos.detalhes-servico', compact(
            'servico',
            'info_clientes',
            'corretagens',
            'op_campos_adicionais',
            'preset_foto',
            'c_percentual',
            'e_principal',
            's_ativo',
            's_inativo',
            's_indisponivel',
            's_pendente',
            'e_categoria_servico',
            'e_categoria_padrao',
            'info_servico',
            'consome_bloqueio',
            'nao_consome_bloqueio',
            'status_servico',
            'integracoes',
            'tipos_variacao',
            'url_online',
            'icones_jam',
            'regra_antecedencia'
        ));
    }

    /**
     * Post de cadastro do serviço
     *
     * @param StoreServicoRequest $request
     * @return array
     */
    public function storeDescricao(StoreServicoRequest $request)
    {
        // Cria um novo serviço com os dados do request
        $servico = Servico::create($request->all());

        if(is_object($servico)) {
            return [
                'action' => true,
                'servico' => $servico,
                'destino' => route('app.destinos.view.json', $servico->destino_id),
                'view' => route('app.servicos.view', $servico->id),
            ];
        }

        return ['action' => false];
    }

    /**
     * Atualização da descrição do serviço
     *
     * @param UpdateServicoRequest $request
     * @return array
     */
    public function updateDescricao(UpdateServicoRequest $request)
    {
        // Cria um novo serviço com os dados do request
        $servico = Servico::find($request->get('servico_id'));

        // Corretagem caso seja sem coloca como 0
        $corregatem = ($request->get('tipo_corretagem') == ServicoEnum::SEM_CORRETAGEM) ? 0 : $request->get('corretagem');

        // Dados para atualizar o serviço
        $dados_update = $request->except('canal_venda_id');

        // Sobreescreve a corretagem
        $dados_update['corretagem'] = $corregatem;

        if($dados_update['hora_maxima_antecedencia'] == '00:00') $dados_update['hora_maxima_antecedencia'] = null;

        // Atualiza o serviço
        $update = $servico->update($dados_update);

        // Caso foi atualizado
        if($update) {
            return $this->autoResponseJson(true, "Serviço atualizado", "As informações do serviço foram salvas com sucesso!");
        }

        return $this->autoResponseJson(false, "Não foi possível atualizar", "O serviço não foi atualizado, tente novamente!");
    }

    /**
     * Post para ligar a categoria ao serviço
     *
     * @param StoreCategoriaServicoRequest $request
     * @return array
     */
    public function storeCategoria(StoreCategoriaServicoRequest $request)
    {
        // Encontra o servico
        $servico = Servico::with('categorias')->find($request->get('servico_id'));

        // Recupera a categoria
        $categoria = Categoria::find($request->get('categoria_id'));

        // Tipo da categoria
        $tipo_categoria = $request->get('padrao', CategoriasEnum::CATEGORIA_NORMAL);

        // Caso encontre o servico
        if(is_object($servico)) {

            // Caso seja para deixar a categoria como padrao
            if($tipo_categoria == CategoriasEnum::CATEGORIA_PADRAO) {

                // Recupera as categorias do servico
                $categorias_servico = $servico->categorias;

                // Adiciona a categoria cadastrada como padrao
                $update[$categoria->id] = [
                    'servico_id' => $servico->id,
                    'categoria_id' => $categoria->id,
                    'padrao' => CategoriasEnum::CATEGORIA_PADRAO
                ];

                // Monta o novo array com as categorias e deixa somente a que está sendo atualizada como PADRAO
                foreach ($categorias_servico as $index => $categoria_servico) {
                    // Copia as categorias no servico
                    $update[$categoria_servico->id] = $categoria_servico->pivot->toArray();
                    $update[$categoria_servico->id]['padrao'] = CategoriasEnum::CATEGORIA_NORMAL;
                }

                // Atualiza as categorias do servico
                $servico->categorias()->sync($update);

            } else {

                // Salva a categoria no servico
                $servico->categorias()->attach([$categoria->id]);
            }

            // Salva as secoes com o servico
            $servico->secoesCategoria()->attach($request->get('secoes'));

            return $this->autoResponseJson(true, "Categoria adicionada", "A categoria {$categoria->nome} foi adicionada ao serviço!");
        }

        return $this->autoResponseJson(false, 'Serviço não encontrado', 'Não foi possível localizar o serviço, tente novamente!');
    }

    /**
     * Put para atualização ou remoção da categoria e das secoes no servico
     *
     * @param UpdateCategoriaServicoRequest $request
     * @return array
     */
    public function updateCategoriaServico(UpdateCategoriaServicoRequest $request)
    {
        // Recupera a categoria
        $categoria = Categoria::with('secoesCategoria')->find($request->get('categoria_id'));

        // Recupera o servico com as secoes das outras categorias
        $servico = Servico::with([
            'categorias',
            'secoesCategoria' => function($query) use ($categoria) {
                return $query->where('categoria_id', '<>', $categoria->id);
            }
        ])->select(['id', 'canal_venda_id'])->find($request->get('servico_id'));

        // Merge das secoes das outras categorias com o servico
        $secoes = $servico->secoesCategoria->pluck('id')->merge($request->get('secoes'));

        // Caso seja para deletar a categoria do servico
        $delete_category = $request->get('delete_category');

        // Caso seja para atualizar a categoria
        if($delete_category == "off") {

            // Caso seja para deixar a categoria como padrao
            if($request->get('padrao') == CategoriasEnum::CATEGORIA_PADRAO) {

                // Recupera as categorias do servico
                $categorias_servico = $servico->categorias; $update = [];

                // Monta o novo array com as categorias e deixa somente a que está sendo atualizada como PADRAO
                foreach ($categorias_servico as $index => $categoria_servico) {
                    // Copia as categorias no servico
                    $update[$categoria_servico->id] = $categoria_servico->pivot->toArray();
                    $update[$categoria_servico->id]['padrao'] = CategoriasEnum::CATEGORIA_NORMAL;
                    // Caso seja a categoria que deve ficar como padrao atualiza a hora e coloca como padrao
                    if($categoria_servico->id == $categoria->id) {
                        $update[$categoria_servico->id]['padrao'] = CategoriasEnum::CATEGORIA_PADRAO;
                        $update[$categoria_servico->id]['updated_at'] = now();
                    }
                }

                // Atualiza as categorias do servico
                $servico->categorias()->sync($update);
            }

            // Atualiza as secoes no servico
            $update = $servico->secoesCategoria()->sync($secoes);

            // Verifica se atualizou
            if($update) {
                return $this->autoResponseJson(true, "Seções atualizadas", "As seções foram salvas no serviço!");
            }

            return $this->autoResponseJson(false, "Seções não atualizadas", "As seções não foram salvas, tente novamente!");

        } else {
            // Remove a categoria do servico
            $servico->categorias()->detach([$categoria->id]);

            // Remove as secoes do servico
            $servico->secoesCategoria()->detach($categoria->secoesCategoria->pluck('id'));

            return $this->autoResponseJson(true, "Categoria removida", "A categoria {$categoria->nome} foi removida do serviço!");
        }
    }

    /**
     * Retorna o servico com a categoria e as secoes
     *
     * @param $servico_id
     * @param $categoria_id
     * @return mixed
     */
    public function viewCategoriaServico($servico_id, $categoria_id)
    {
        return Servico::with([
            'categorias.destino',
            'categorias.secoesCategoria',
            'categorias' => function ($q) use ($categoria_id) {
                return $q->where('id', $categoria_id);
            },
            'secoesCategoria' => function ($q) use ($categoria_id) {
                return $q->where('categoria_id', $categoria_id);
            }
        ])->select(['canal_venda_id', 'id', 'destino_id'])->find($servico_id);
    }

    /**
     * Post para cadastro das regras e observacoes no voucher
     *
     * @param Request $request
     * @return array
     */
    public function storeRegras(Request $request)
    {
        // Recupera o servico
        $servico = Servico::find($request->get('servico_id'));

        // Caso nao esteja vazio
        if(is_object($servico)) {

            // Atualiza as regras e observacoes
            $servico->update([
                'regras' => $request->get('regras'),
                'observacao_voucher' => $request->get('observacao_voucher')
            ]);

            return $this->autoResponseJson(true, 'Regras e observações cadastradas', 'As regras e observações no voucher foram cadastradas com sucesso!');
        }

        return $this->autoResponseJson(false, 'Serviço não encontrado', 'O serviço não foi localizado, tente novamente!');
    }

    /**
     * Post para atualizar as regras e observacoes no voucher
     *
     * @param Request $request
     * @return array
     */
    public function updateRegras(Request $request)
    {
        // Recupera o servico
        $servico = Servico::find($request->get('servico_id'));

        // Caso nao esteja vazio
        if(is_object($servico)) {

            // Atualiza as regras e observacoes
            $servico->update([
                'regras' => $request->get('regras'),
                'observacao_voucher' => $request->get('observacao_voucher')
            ]);

            return $this->autoResponseJson(true, 'Regras e observações atualizadas', 'As regras e observações no voucher foram salvas com sucesso!');
        }

        return $this->autoResponseJson(false, 'Serviço não encontrado', 'O serviço não foi localizado, tente novamente!');
    }

    public function calendario()
    {
        $uuid = request()->get('uuid');

        if (request()->ajax()) {

            // Recupera os dados do servico
            $agenda = AgendaService::disponibilidadeSite($uuid);

            // Recupera os dados selecionados no carrinho
            if (request()->get('carrinho') == "true") {
                $agenda['carrinho'] = carrinho()->get($uuid) ?? [];
                $agenda['carrinho']['urls']['carrinho'] = route('ecommerce.carrinho.add');
            }

            return $agenda;
        }

        return redirect()->route('ecommerce.index');
    }
}
