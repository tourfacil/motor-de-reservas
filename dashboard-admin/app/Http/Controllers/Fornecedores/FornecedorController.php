<?php namespace App\Http\Controllers\Fornecedores;

use App\Enum\BancosEnum;
use App\Enum\UserLevelEnum;
use App\Http\Requests\StoreDadosBancariosFornecedor;
use App\Http\Requests\StoreFornecedorRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateFornecedorRequest;
use Illuminate\Http\Request;
use TourFacil\Core\Enum\Faturas\TipoFaturaEnum;
use TourFacil\Core\Enum\Faturas\TipoPeriodoFaturaEnum;
use TourFacil\Core\Models\CanalVenda;
use TourFacil\Core\Models\DadosBancariosFornecedor;
use TourFacil\Core\Models\Fornecedor;
use TourFacil\Core\Models\Servico;

/**
 * Class FornecedorController
 * @package App\Http\Controllers\Fornecedores
 */
class FornecedorController extends Controller
{
    /**
     * Listagem dos fornecedores
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $fornecedores = Fornecedor::orderBy('created_at', 'DESC')->get();

        return view('paginas.fornecedores.fornecedores', compact(
            'fornecedores'
        ));
    }

    /**
     * View para cadastro do fornecedor
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $bancos = BancosEnum::BANCOS;

        $tipo_contas = BancosEnum::TIPO_CONTAS;

        $tipo_faturas = TipoFaturaEnum::TIPOS;

        $tipo_periodo_faturas = TipoPeriodoFaturaEnum::TIPOS_PERIODO;


        return view('paginas.fornecedores.novo-fornecedor', compact(
            'bancos', 'tipo_contas', 'tipo_faturas', 'tipo_periodo_faturas'
        ));
    }

    /**
     * POST para cadastro do fornecedor
     *
     * @param StoreFornecedorRequest $request
     * @return array
     */
    public function store(StoreFornecedorRequest $request)
    {
        $fornecedor = Fornecedor::create($request->all());

        if(is_object($fornecedor)) {
            return [
                'action' => true,
                'partner' => $fornecedor,
                'view' => route('app.fornecedores.view', $fornecedor->id),
            ];
        }

        return ['action' => false];
    }

    /**
     * PUT para editar os dados do fornecedor
     *
     * @param UpdateFornecedorRequest $request
     * @return array
     */
    public function update(UpdateFornecedorRequest $request)
    {
        // Procura o fornecedor
        $fornecedor = Fornecedor::find($request->get('fornecedor_id'));

        // Verifica se encontrou o fornecedor
        if(is_object($fornecedor)) {
            // Atualiza os dados
            dd($request->all());
            $fornecedor->update($request->all());

            return $this->autoResponseJson(true, "Cadastro atualizado", "As informações do fornecedor foram salvas com sucesso!");
        }

        return $this->autoResponseJson(false, "Cadastro não atualizado", "Não foi possível atualizar os dados do fornecedor, tente novamente!");
    }

    /**
     * Detalhes do fornecedor
     *
     * @param $fornecedor_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function view($fornecedor_id)
    {
        $fornecedor = Fornecedor::with([
            'dadosBancarios',
            'splits.canalVenda',
            'usuarios' => function($q) {
                return $q->withTrashed();
            }
        ])->findOrFail($fornecedor_id);

        // Recupera os canais que ja estao cadastrado o split
        $split_cadastrados = $fornecedor->splits->pluck('canal_venda_id')->toArray();

        // Recupera os canais que nao possuem split para o fornecedor
        $canais_venda_split = CanalVenda::whereNotIn('id', $split_cadastrados)->get(['id', 'site']);

        $bancos = BancosEnum::BANCOS;

        $tipo_contas = BancosEnum::TIPO_CONTAS;

        $niveis_usuarios = UserLevelEnum::LEVELS;

        $tipo_faturas = TipoFaturaEnum::TIPOS;

        $tipo_periodo_faturas = TipoPeriodoFaturaEnum::TIPOS_PERIODO;

        return view('paginas.fornecedores.detalhes-fornecedor', compact(
            'fornecedor',
            'bancos',
            'tipo_contas',
            'canais_venda_split',
            'niveis_usuarios',
            'tipo_faturas',
            'tipo_periodo_faturas'
        ));
    }

    /**
     * Consulta os dados do CNPJ na API
     *
     * @param null $cnpj
     * @return mixed
     */
    public function consultaCNPJ($cnpj = null)
    {
        // Consuta se o CNPJ já está em uso
        $has_cnpj = Fornecedor::where('cnpj', $cnpj)->first();

        if(is_object($has_cnpj)) return ['has_cnpj' => true];

        return consultaCNPJ($cnpj);
    }

    /**
     * Cadastra os dados bancarios do fornecedor
     *
     * @param StoreDadosBancariosFornecedor $request
     * @return array
     */
    public function storeBankDetails(StoreDadosBancariosFornecedor $request)
    {
        $dados_bancarios = DadosBancariosFornecedor::create($request->all());

        return ['action' => is_object($dados_bancarios)];
    }

    /**
     * PUT para editar os dados bancários do fornecedor
     *
     * @param StoreDadosBancariosFornecedor $request
     * @return array
     */
    public function updateBankDetails(StoreDadosBancariosFornecedor $request)
    {
        // Procura os dados do fornecedor
        $dados_bancarios = DadosBancariosFornecedor::where('fornecedor_id', $request->get('fornecedor_id'))->first();

        $fornecedor = Fornecedor::find($request->get('fornecedor_id'));

        // Verifica se encontrou os dados
        if(is_object($dados_bancarios)) {
            // Atualiza os dados
            $dados_bancarios->update($request->all());

            $fornecedor->update($request->all());

            return $this->autoResponseJson(true, "Dados bancários atualizado", "As informações do fornecedor foram salvas com sucesso!");
        }

        return $this->autoResponseJson(false, "Dados bancários não atualizado", "Não foi possível atualizar os dados bancários, tente novamente!");
    }

    /**
     * Cadastra as regras e termos do fornecedor
     *
     * @param Request $request
     * @return array
     * @throws \Illuminate\Validation\ValidationException
     */
    public function storeRules(Request $request)
    {
        // Valida o formulário
        $this->validate($request, ['fornecedor_id' => 'required|integer|exists:fornecedores,id']);

        // Caso o fornecedor possua um termo
        if($request->get('termos') != null) {
            // Procura o fornecedor
            $fornecedor = Fornecedor::findOrFail($request->get('fornecedor_id'));
            // Atualiza os dados
            $fornecedor->update(['termos' => $request->get('termos')]);
        }

        return ['action' => true];
    }

    /**
     * Atualiza as regras e termos do fornecedor
     *
     * @param Request $request
     * @return array
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updateRules(Request $request)
    {
        // Valida o formulário
        $this->validate($request, ['fornecedor_id' => 'required|integer|exists:fornecedores,id']);

        // Procura o fornecedor
        $fornecedor = Fornecedor::findOrFail($request->get('fornecedor_id'));

        // Atualiza os dados
        $update = $fornecedor->update(['termos' => $request->get('termos')]);

        // Verifica se atualizou
        if($update) {
            return $this->autoResponseJson(true, "Regras e termos atualizados", "As regras e termos do fornecedor foram salvas com sucesso!");
        }

        return $this->autoResponseJson(false, "Regras e termos não atualizados", "Não foi possível atualizar as regras do fornecedor, tente novamente!");
    }

    /**
     * Lista dos serviços do fornecedor
     *
     * @param $fornecedor_id
     * @return mixed
     */
    public function viewServicosJson($fornecedor_id)
    {
        // Canal de venda na sessao do usuario
        $canal_venda = canalSession()->getCanal();

        return Servico::where([
            'fornecedor_id' => $fornecedor_id,
            'canal_venda_id' => $canal_venda->id,
        ])->get(['id', 'nome']);
    }
}
