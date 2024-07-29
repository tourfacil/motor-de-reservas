<?php namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use TourFacil\Core\Models\RankingServico;
use TourFacil\Core\Services\ServicoService;

/**
 * Class RankingServicos
 * @package App\Console\Commands
 */
class RankingServicos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'servicos:ranking';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Atualiza o ranking dos serviços';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $vendas = [];
        $results = [];
        $rankings = [];

        // Periodos de pesquisa
        $periodos = periodoPesquisa();

        // Adiciona o periodo ultimo ano
        $periodos['ultimo_ano'] = [
            'start' => Carbon::today()->subYears(1)->startOfDay(),
            'end' => Carbon::today()->endOfDay()
        ];

        // Remove o periodo de hoje
        unset($periodos['hoje']);

        // Faz a pesquisa para cada periodo
        foreach ($periodos as $chave => $periodo) {
            $vendas[$chave] = ServicoService::rankingServicos($periodo['start'], $periodo['end']);
        }

        // Separa os dados por servico
        foreach ($vendas as $chave => $servicos) {
            foreach ($servicos as $servico) {
                $results[$servico->id]['servico_id'] = $servico->id;
                $results[$servico->id]['vendido'][$chave] = (int) $servico->vendas;
            }
        }

        // Calcula a media para ser usada como ranking
        foreach ($results as $dado_servico) {

            // Recupera os dados de venda
            $dados_venda = array_values($dado_servico['vendido']);

            // Media de ingressos vendidos
            $average = array_sum($dados_venda) / count($dados_venda);

            // Usa a media como ranking
            $results[$dado_servico['servico_id']]['ranking'] = (int) number_format($average, 0);

            // Recupera os dados do ranking atual
            $ranking = RankingServico::where('servico_id', $dado_servico['servico_id'])->first();

            // Nao nao exista cria um novo ranking senao atualiza os dados
            if(is_null($ranking)) {
                $rankings[] = RankingServico::create([
                    "servico_id" => $dado_servico['servico_id'],
                    "ranking" => $results[$dado_servico['servico_id']]['ranking'],
                    "vendido" => $dado_servico['vendido'],
                ]);
            } else {
                $rankings[] = $ranking->update([
                    "ranking" => $results[$dado_servico['servico_id']]['ranking'],
                    "vendido" => $dado_servico['vendido'],
                ]);
            }
        }

        // Reseta o cache dos sites
        $this->call('canal-venda:reset-cache');
    }
}
