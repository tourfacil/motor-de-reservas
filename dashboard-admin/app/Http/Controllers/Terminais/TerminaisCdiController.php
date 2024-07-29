<?php namespace App\Http\Controllers\Terminais;

use App\Http\Requests\Terminais\StoreTerminaisRequest;
use App\Http\Requests\Terminais\UpdateTerminaisRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use TourFacil\Core\Enum\CanaisVendaEnum;
use TourFacil\Core\Enum\ComissaoStatus;
use TourFacil\Core\Enum\EstadosEnum;
use TourFacil\Core\Enum\TerminaisEnum;
use TourFacil\Core\Models\CanalVenda;
use TourFacil\Core\Models\ComissaoTerminal;
use TourFacil\Core\Models\Destino;
use TourFacil\Core\Models\Fornecedor;
use TourFacil\Core\Models\HistoricoConexaoTerminal;
use TourFacil\Core\Models\Terminal;
use TourFacil\Core\Services\TerminalService;

/**
 * Class TerminaisCdiController
 * @package App\Http\Controllers\Terminais
 */
class TerminaisCdiController extends Controller
{
    /**
     * Listagem dos terminais de venda
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        // Recupera os terminais
        $terminais = Terminal::withTrashed()->get();

        $url_terminais = CanaisVendaEnum::URL_TERMINAIS_CDI;

        return view('paginas.terminais.terminais', compact(
            'terminais',
            'url_terminais'
        ));
    }

    /**
     * View para cadastro de um novo terminal
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        // Estados BR
        $estados = EstadosEnum::ESTADOS;

        // Fornecedores (parceiros)
        $fornecedores = Fornecedor::all();

        // Fabricantes dos terminais
        $fabricantes = TerminaisEnum::FABRICANTES;

        // Canal de venda terminais CDI
        $canal_terminais = CanalVenda::find(CanaisVendaEnum::TERMINAIS_CDI);

        // Recupera os destinos do canal de venda
        $destinos = Destino::where('canal_venda_id', $canal_terminais->id)->get();

        return view('paginas.terminais.novo-terminal', compact(
            'estados',
            'fornecedores',
            'canal_terminais',
            'fabricantes',
            'destinos'
        ));
    }

    /**
     * Post para novo terminal
     *
     * @param StoreTerminaisRequest $request
     * @return array
     */
    public function store(StoreTerminaisRequest $request)
    {
        $terminal = Terminal::create($request->all());

        if(is_object($terminal)) {
            return $this->autoResponseJson(
                true,
                "Terminal cadastrado",
                "As informações sobre o terminal foram salvas com sucesso!",
                route('app.terminais.view', $terminal->id)
            );
        }

        return $this->autoResponseJson(false, "Terminal não cadastrado", "Não foi possível cadastrar o terminal, tente novamente!");
    }

    /**
     * View para detalhes do terminal
     *
     * @param $id_terminal
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function view($id_terminal)
    {
        // Recupera as informacoes do terminal
        $terminal = Terminal::with([
            'usuarios',
            'historicoConexao',
            'debitoComissao.comissaoTerminal.reservaPedido',
            'comissaoTerminal' => function ($q) {
                return $q->limit(20)->with('reservaPedido.servico');
            }
        ])->withTrashed()->findOrFail($id_terminal);

        // Estados
        $estados = EstadosEnum::ESTADOS;

        // Fornecedores (parceiros)
        $fornecedores = Fornecedor::all();

        // URl para configurar o terminal
        $url_terminais = CanaisVendaEnum::URL_TERMINAIS_CDI;

        // URL de login no terminal
        $url_login_terminal = CanaisVendaEnum::URL_TERMINAIS_LOGIN;

        // Fabricantes dos terminais
        $fabricantes = TerminaisEnum::FABRICANTES;

        // Canal de venda terminais CDI
        $canal_terminais = CanalVenda::find(CanaisVendaEnum::TERMINAIS_CDI);

        // Recupera os destinos do canal de venda
        $destinos = Destino::where('canal_venda_id', $canal_terminais->id)->get();

        // Ultima conexao do terminal
        $ultima_conexao = $terminal->historicoConexao->first();

        // Comissoes de vendas terminal
        $comissoes = TerminalService::comissaoAdministrativo($terminal->id);

        return view('paginas.terminais.detalhes-terminal', compact(
            'terminal',
            'estados',
            'fornecedores',
            'url_terminais',
            'url_login_terminal',
            'fabricantes',
            'canal_terminais',
            'destinos',
            'ultima_conexao',
            'comissoes'
        ));
    }

    /**
     * Atualiza ou desativa um terminal
     *
     * @param UpdateTerminaisRequest $request
     * @return array
     */
    public function update(UpdateTerminaisRequest $request)
    {
        // Recupera as informações do terminal
        $terminal = Terminal::withTrashed()->findOrFail($request->get('terminal_id'));

        // Status do terminal
        $status = $request->get('disable');

        // Se for para desativar o terminal
        if(is_null($status)) {

            // Desativa o terminal
            $delete = $terminal->delete();

            // Desativar os usuarios
            $terminal->usuarios()->delete();

            if($delete) {
                return $this->autoResponseJson(true, "Terminal desativado", "O terminal foi desativado com sucesso!");
            }

            return $this->autoResponseJson(false, "Terminal não desativado", "Não foi possível desativar o terminal, tente novamente!");
        }

        // Caso seja para reativar o terminal
        if($status == "on" && $terminal->status == false) {

            // Reativa o terminal
            $restore = $terminal->restore();

            // Reativar os usuarios

            if($restore) {
                return $this->autoResponseJson(true, "Terminal reativado", "O terminal foi reativado com sucesso!");
            }

            return $this->autoResponseJson(false, "Terminal ainda desativado", "Não foi possível reativar o terminal, tente novamente!");
        }

        // Atualiza as informações do terminal
        $update = $terminal->update($request->all());

        if($update) {
            return $this->autoResponseJson(true, "Terminal atualizado", "As informações sobre o terminal foram atualizadas com sucesso!");
        }

        return $this->autoResponseJson(false, "Terminal não atualizado", "Não foi possível atualizar o terminal, tente novamente!");
    }

    /**
     * Detalhe das vendas do terminal
     *
     * @param $terminal_id
     * @param $mes
     * @return mixed
     */
    public function viewPrevisaoMes($terminal_id, $mes)
    {
        // Inicio da pesquisa
        $inicio = Carbon::parse("01-$mes");

        // Final da pesquisa
        $final = $inicio->copy()->endOfMonth();

        return [
            'url_reserva' => route('app.reservas.view'),
            'pago' => ComissaoStatus::PAGO,
            'cores' => ComissaoStatus::CORES_STATUS,
            'cancelado' => ComissaoStatus::CANCELADO,
            'aguardando' => ComissaoStatus::AGUARDANDO,
            'mes' => mesPT($inicio->month) . " " . $inicio->year,
            'vendas' => ComissaoTerminal::with('reservaPedido.servico')
                ->whereBetween('data_previsao', [$inicio, $final])
                ->where('terminal_id', $terminal_id)->get()
        ];
    }

    /**
     * Detalhes da conexao do terminal
     *
     * @param $historico_id
     * @return mixed
     */
    public function viewHistorico($historico_id)
    {
        return HistoricoConexaoTerminal::with('terminal')->find($historico_id);
    }
}
