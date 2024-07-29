<?php namespace App\Http\Controllers;

use App\Console\Commands\VerificaDisponibilidade;
use App\Enum\ConfiguracoesEnum;
use App\Http\Requests\UpdateDataUserRequest;
use App\Mail\DemoMail;
use App\Models\User;
use App\Services\DashboardService;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use TourFacil\Core\Enum\ServicoEnum;
use TourFacil\Core\Models\Afiliado;
use TourFacil\Core\Models\CanalVenda;
use TourFacil\Core\Models\ReservaPedido;
use TourFacil\Core\Models\Servico;
use TourFacil\Core\Models\Vendedor;
use TourFacil\Core\Services\CanalVendaService;
use TourFacil\Core\Services\ServicoService;
use TourFacil\Core\Services\Snowland\SnowlandService;

/**
 * Class PainelController
 * @package App\Http\Controllers
 */
class PainelController extends Controller
{
    use ResetsPasswords;

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {

        // Canal de venda atual
        $canal_venda = canalSession()->getCanal();

        // Servicos mais vendidos nos terminais
        $servicos_mais_vendidos = [];
        if(userIsAfiliado()) {

            $servicos_mais_vendidos = ServicoService::servicosMaisVendidosAfiliado($canal_venda->id, 8, Afiliado::find(auth()->user()->afiliado_id));
        } elseif(userIsVendedor()) {

            $servicos_mais_vendidos = ServicoService::servicosMaisVendidosVendedor($canal_venda->id, 8, Vendedor::find(auth()->user()->vendedor_id));
        } else {

            $servicos_mais_vendidos = ServicoService::servicosMaisVendidos($canal_venda->id, 8);
        }

        // View para usuarios admin
        if(userIsAdmin()) {
            return $this->dashboardAdmin($canal_venda, $servicos_mais_vendidos);
        }

        // View para os vendedores
        if(userIsVendedor()) {
            return $this->dashboardVendedor($canal_venda, $servicos_mais_vendidos);
        }

        // View para os afiliados
        if(userIsAfiliado()) {
            return $this->dashboardAfiliado($canal_venda, $servicos_mais_vendidos);
        }



        return $this->dashboardBasico($canal_venda, $servicos_mais_vendidos);
    }

    /** Rota para testes */
    public function artisan()
    {
        dd('aqui');

        //\Artisan::call('servicos:home');

        //dd($servico);
    }

    /**
     * Dashboard nivel basico
     *
     * @param $canal_venda
     * @param $servicos_mais_vendidos
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    private function dashboardBasico($canal_venda, $servicos_mais_vendidos)
    {
        // Novos serviços dos ultimos 7 dias
        $novos_servicos = Servico::select('id', 'fornecedor_id', 'nome', 'valor_venda', 'status', 'created_at')
            ->with(['fornecedor' => function ($q) { return $q->select('id', 'nome_fantasia'); }])
            ->where('canal_venda_id', $canal_venda->id)->limit(30)->latest()->get();

        // Quantidade total disponivel para venda
        $cadastrados = Servico::whereIn('status', [ServicoEnum::ATIVO])
            ->where('canal_venda_id', $canal_venda->id)->count('id');

        // Quantidade de servicos pendentes para analise
        $pendentes = Servico::whereIn('status', [ServicoEnum::PENDENTE])
            ->where('canal_venda_id', $canal_venda->id)->count('id');

        // Quantidade de servicos desativados ou inativos
        $desativados = Servico::whereIn('status', [
            ServicoEnum::INATIVO, ServicoEnum::INDISPONIVEL
        ])->where('canal_venda_id', $canal_venda->id)->count('id');

        // Quantidade de servicos vendidos
        $qtd_servicos_vendidos = $servicos_mais_vendidos->count();

        // Dados para o grafico
        $chart = $this->dadosChartVendasBasico($novos_servicos);

        return view('home-basico', compact(
            'servicos_mais_vendidos',
            'novos_servicos',
            'cadastrados',
            'pendentes',
            'desativados',
            'qtd_servicos_vendidos',
            'chart'
        ));
    }

    /**
     * Dados para o grafico de servicos cadastrados
     *
     * @param $ultimos_servicos
     * @return array
     */
    private function dadosChartVendasBasico($ultimos_servicos)
    {
        // Data de hoje
        //$hoje = Carbon::today()->endOfDay();
        $hoje = Carbon::today()->endOfDay();

        // Periodo de pesquisa
        $periodo_pesquisa = Carbon::today()->subDays(10);

        // Datas do periodo
        $periodo = CarbonPeriod::create($periodo_pesquisa, $hoje);

        // Array para o chart
        $return = [
            'labels' => [],
            'data_set' => [],
            'dados' => [],
            'quantidade_total' => 0
        ];

        foreach ($periodo as $data) {

            // Data formatada para index
            $data_formatada = $data->format('d/m');

            // Recupera os servicos cadastrados no dia
            $servicos = $ultimos_servicos->filter(function ($servico) use ($data) {
                return ($servico->created_at->format('dmY') == $data->format('dmY'));
            });

            // Quantidade cadastrada
            $quantidade = $servicos->count();

            // Labels do grafico
            $return['labels'][] = $data_formatada;

            // Dados de venda
            $return['data_set'][] = $quantidade;
            $return['quantidade_total'] += $quantidade;

            // Cria a casa por dia da semana
            $return['dados'][] = [
                'data' => $data_formatada,
                'semana' => semanaPT($data->dayOfWeekIso, true),
                'mes' => mesPT($data->month, true),
                'vendido' => $quantidade,
                'dia' => $data->format('d')
            ];
        }

        return $return;
    }

    /**
     * Dashboard admin
     *
     * @param $canal_venda
     * @param $servicos_mais_vendidos
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    private function dashboardAdmin($canal_venda, $servicos_mais_vendidos)
    {
        // Dados das vendas
        $dashboard = CanalVendaService::dashboardCanal($canal_venda);

        // Dados para o grafico
        $chart = $this->dadosChartVendas($dashboard['ultimas_vendas']);

        // Quantidade de servicos vendidos
        $qtd_servicos_vendidos = $servicos_mais_vendidos->count();

        $destinos_mais_vendidos = DashboardService::GetValorPorDestinoMes();
        $contador_destino = 0;

        return view('home', compact(
            'dashboard',
            'chart',
            'servicos_mais_vendidos',
            'qtd_servicos_vendidos',
            'destinos_mais_vendidos',
            'contador_destino'
        ));
    }

    private function dashboardVendedor($canal_venda, $servicos_mais_vendidos) {

        $user = auth()->user();
        $afiliado = null;
        $vendedor = null;

        if(userIsAfiliado()) {
            $afiliado = Afiliado::find($user->afiliado_id);
        } else {
            $vendedor = Vendedor::find($user->vendedor_id);
        }


        // Dados das vendas
        $dashboard = CanalVendaService::dashboardCanal($canal_venda, $afiliado, $vendedor);

        // Dados para o grafico
        $chart = $this->dadosChartVendas($dashboard['ultimas_vendas']);

        // Quantidade de servicos vendidos
        $qtd_servicos_vendidos = $servicos_mais_vendidos->count();

        $dashboard['comissao'] = ($dashboard['vendas_mes'] * 1.5) / 100;

        return view('home', compact(
            'dashboard',
            'chart',
            'servicos_mais_vendidos',
            'qtd_servicos_vendidos'
        ));
    }

    private function dashboardAfiliado($canal_venda, $servicos_mais_vendidos) {

        $user = auth()->user();
        $afiliado = null;
        $vendedor = null;

        if(userIsAfiliado()) {
            $afiliado = Afiliado::find($user->afiliado_id);
        } else {
            $vendedor = Vendedor::find($user->vendedor_id);
        }


        // Dados das vendas
        $dashboard = CanalVendaService::dashboardCanal($canal_venda, $afiliado, $vendedor);

        // Dados para o grafico
        $chart = $this->dadosChartVendas($dashboard['ultimas_vendas']);

        // Quantidade de servicos vendidos
        $qtd_servicos_vendidos = $servicos_mais_vendidos->count();

        $dashboard['comissao'] = ($dashboard['vendas_mes'] * 1.5) / 100;

        return view('home', compact(
            'dashboard',
            'chart',
            'servicos_mais_vendidos',
            'qtd_servicos_vendidos'
        ));
    }

    /**
     * Dados para o grafico do dashboard
     *
     * @param $ultimos_pedidos
     * @return array
     */
    private function dadosChartVendas($ultimos_pedidos)
    {
        // Data de hoje
        $hoje = Carbon::today()->endOfDay();

        // Periodo de pesquisa
        $periodo_pesquisa = Carbon::today()->subDays(10);

        // Datas do periodo
        $periodo = CarbonPeriod::create($periodo_pesquisa, $hoje);

        // Array para o chart
        $return = [
            'labels' => [],
            'data_set' => [],
            'dados' => []
        ];

        foreach ($periodo as $data) {

            // Data formatada para index
            $data_formatada = $data->format('d/m');

            // Recupera as reservas realizadas no dia
            $pedidos = $ultimos_pedidos->filter(function ($pedido) use ($data) {
                return ($pedido->created_at->format('dmY') == $data->format('dmY'));
            });

            // Quantidade vendida
            $quantidade = 0;

            // Percorre os pedidos para somar a quantidade
            foreach ($pedidos as $pedido) {
                //$quantidade += $pedido->reservas->sum('valor_total');

                if(in_array($pedido->status, ['PAGO', 'UTILIZADO'])) {

                    $quantidade += $pedido->valor_total + $pedido->juros;
                }

            }

            // $quantidade += "R$ " . number_format($pedido->valor_total + $pedido->juros, 2, ',', '.');

            // Labels do grafico
            $return['labels'][] = $data_formatada;

            // Dados de venda
            $return['data_set'][] = floatVal(number_format($quantidade, 2, ',', ''));

            // Cria a casa por dia da semana
            $return['dados'][] = [
                'data' => $data_formatada,
                'semana' => semanaPT($data->dayOfWeekIso, true),
                'mes' => mesPT($data->month, true),
                'vendido' => $quantidade,
                'dia' => $data->format('d')
            ];
        }

        return $return;
    }

    /**
     * Página de meus dados do usuário logado
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function meusDados()
    {
        $dados = auth()->user();

        return view('paginas.usuarios.meus-dados', compact(
            'dados'
        ));
    }

    /**
     * Atualiza os dados do usuário logado
     *
     * @param UpdateDataUserRequest $request
     * @return array
     */
    public function atualizarDados(UpdateDataUserRequest $request)
    {
        $updated = User::find(auth()->user()->id)->update($request->all());

        if($updated) {
            return $this->autoResponseJson(true, "Dados atualizados", "Seus dados foram salvos com sucesso!");
        }

        return $this->autoResponseJson(false, "Não possível atualizar", "Seus dados não foram salvos, tente novamente!");
    }

    /**
     * View para alteração da senha
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function viewAlterarSenha()
    {
        $dados = auth()->user();

        return view('paginas.usuarios.alterar-senha', compact(
            'dados'
        ));
    }

    /**
     * Post para alterar senha de usuário
     *
     * @param Request $request
     * @return array
     * @throws \Illuminate\Validation\ValidationException
     */
    public function alterarSenha(Request $request)
    {
        // Valida o formulário
        $this->validate($request, ['password' => 'required|confirmed|min:6']);

        // Dados do usuário logado
        $user = auth()->user();

        $this->resetPassword($user, $request->get('password'));

        return $this->autoResponseJson(true, "Senha alterada!", "Sua senha de acesso foi alterada com sucesso!");
    }

    /**
     * Página de configurações do usuario
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function viewConfiguracoesUsuario()
    {
        $dados = auth()->user();

        $pages = ConfiguracoesEnum::PAGES;

        return view('paginas.usuarios.configuracoes', compact(
            'dados', 'pages'
        ));
    }

    /**
     * Atualiza as configurações do usuario
     *
     * @param Request $request
     * @return array
     * @throws \Illuminate\Validation\ValidationException
     */
    public function atualizarConfiguracoesUsuario(Request $request)
    {
        // Valida o formulário
        $this->validate($request, ['canal_venda_id' => 'required|integer|exists:canal_vendas,id']);

        // Recupera o canal de venda
        $canal_venda = CanalVenda::find($request->get('canal_venda_id'));

        // Caso tenho o canal de venda
        if(is_object($canal_venda)) {

            // Atualiza o canal na sessao
            canalSession()->setCanal($canal_venda);

            // Atualiza as configuracoes
            User::find(auth()->user()->id)->update($request->all());

            return $this->autoResponseJson(
                true,
                "Configurações atualizadas",
                "Suas configurações foram salvas com sucesso!",
                route('app.dashboard')
            );
        }

        return $this->autoResponseJson(false, "Não foi possível atualizar", "Suas configurações não foram salvas, tente novamente!");
    }

    /**
     * Atualiza o canal de venda na sessao
     *
     * @param Request $request
     * @return array
     */
    public function trocarCanalSessao(Request $request)
    {
        // Recupera o canal do request senão retorna o canal default
        $canal_request = $request->get('canal_id', canalSession()->getCanal()->id);

        // Procura o canal de venda
        $canal = CanalVenda::find($canal_request);

        // Caso encontre o canal
        if(is_object($canal)) {
            // Atualiza o canal na sessao
            canalSession()->setCanal($canal);
        }

        return ['action' => is_object($canal)];
    }
}
