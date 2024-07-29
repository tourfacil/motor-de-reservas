<?php namespace App\Http\Controllers\Painel;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use TourFacil\Core\Models\ComissaoTerminal;
use TourFacil\Core\Services\FornecedorService;
use TourFacil\Core\Services\ServicoService;
use TourFacil\Core\Services\TerminalService;

/**
 * Class PainelController
 * @package App\Http\Controllers\Painel
 */
class PainelController extends Controller
{
    /**
     * Home dashboard painel
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function dashboard()
    {
        $user = auth()->user();

        // Dados das vendas
        $dashboard = FornecedorService::dashboardFornecedor($user->fornecedor_id);

        // Dados para o grafico
        $chart = $this->dadosChartVendas($dashboard['ultimas_vendas']);

        // Servicos mais vendidos do fornecedor
        $servicos_mais_vendidos = ServicoService::servicosMaisVendidosFornecedor($user->fornecedor_id, 8);

        // Quantidade de servicos vendidos
        $qtd_servicos_vendidos = $servicos_mais_vendidos->count();

        return view("paginas.dashboard", compact(
            'dashboard',
            'chart',
            'servicos_mais_vendidos',
            'qtd_servicos_vendidos'
        ));
    }

    /**
     * Dados para o grafico do dashboard
     *
     * @param $ultimas_reservas
     * @return array
     */
    public function dadosChartVendas($ultimas_reservas)
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
            $reservas = $ultimas_reservas->filter(function ($reserva) use ($data) {
                return ($reserva->created_at->format('dmY') == $data->format('dmY'));
            });

            // Quantidade vendida
            $quantidade = $reservas->sum('quantidade');

            // Labels do grafico
            $return['labels'][] = $data_formatada;

            // Dados de venda
            $return['data_set'][] = $quantidade;

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
}
