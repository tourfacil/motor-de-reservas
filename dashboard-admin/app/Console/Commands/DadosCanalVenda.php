<?php namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use TourFacil\Core\Enum\AgendaEnum;
use TourFacil\Core\Enum\ServicoEnum;
use TourFacil\Core\Models\AgendaDataServico;
use TourFacil\Core\Models\Destino;
use TourFacil\Core\Models\Servico;
use TourFacil\Core\Models\VariacaoServico;
use TourFacil\Core\Services\ServicoService;

/**
 * Class DadosCanalVenda
 * @package App\Console\Commands
 */
class DadosCanalVenda extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'canal-venda:dados';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Atualiza os valores minimos dos canais de vendas';

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
        // Valida a disponibilidade dos servicos
        $this->verificaAgendaServicos();

        // Valores do destino e categoria
        $this->menoresValorDestinoCategoria();
    }

    /** Valida a disponibilidade do servico e valor de venda */
    public function verificaAgendaServicos()
    {
        // Datas para pesquisa
        $start = Carbon::today();
        $end = $start->copy()->addMonths(3)->endOfDay();

        // Recupera os servicos a agenda de 3 meses
        $servicos = Servico::with('agendaServico')
            ->whereIn("status", [ServicoEnum::ATIVO])->get();

        // Percorre os serviços
        foreach($servicos as $servico) {

            // Configuracoes da agenda
            $configuracoes_agenda = $servico->agenda->substituicoes_agenda;

            // Substituicoes agenda
            $substitui_net = $configuracoes_agenda[AgendaEnum::SUBSTITUI_NET] ?? null;
            $substitui_venda = $configuracoes_agenda[AgendaEnum::SUBSTITUI_VENDA] ?? null;

            // Menor valor da agenda
            $menor_net = AgendaDataServico::where([
                'agenda_servico_id' => $servico->agenda->id,
                'status' => AgendaEnum::ATIVO
            ])->where('disponivel', '>=', 1)->whereBetween('data', [$start, $end])->min('valor_net');

            // Verifica se o servico possui agenda
            if(! is_null($menor_net)) {

                // Variacao do servico
                $variacao_servico = VariacaoServico::where('servico_id', $servico->id)
                    ->orderBy('destaque', 'ASC')->orderBy('percentual', 'DESC')->first();

                // Valor net de cada variacao
                $net_variacao =  ($variacao_servico->percentual / 100) * $menor_net;

                // Verifica se possui valores no NET para substituir
                if(is_array($substitui_net)) {
                    $net_variacao = (string) number_format($net_variacao, 2, ".", "");
                    $net_variacao = ($substitui_net[$net_variacao]) ?? $net_variacao;
                }

                // Valor de venda da variacao
                $venda_variacao = $net_variacao * $variacao_servico->markup;

                // Verifica se o servico possui corretagem de valor
                if($servico->tipo_corretagem != ServicoEnum::SEM_CORRETAGEM && ($venda_variacao > 0)) {

                    // Verifica se a corretagem é em percentual
                    if($servico->tipo_corretagem == ServicoEnum::CORRETAGEM_PORCENTUAL) {
                        $venda_variacao += ($venda_variacao / 100 * $servico->corretagem);
                    }

                    // Verifica se a corretagem é em valor fixo
                    if($servico->tipo_corretagem == ServicoEnum::CORRETAGEM_FIXA) {
                        $venda_variacao += $servico->corretagem;
                    }
                }

                // Verifica se possui valores da venda para substituir
                if(is_array($substitui_venda)) {
                    $venda_variacao = (string) number_format($venda_variacao, 2, ".", "");
                    $venda_variacao = $substitui_venda[$venda_variacao] ?? $venda_variacao;
                }

                // Caso o valor de venda seja zero colocar como 1 real
                $venda_variacao = ($venda_variacao == 0) ? 1 : $venda_variacao;

                $venda_variacao = number_format($venda_variacao, 2, ".", "");

                // Atualiza o valor de venda e altera o status do serviço
                $servico->update([
                    "valor_venda" => $venda_variacao, "status" => ServicoEnum::ATIVO
                ]);

            } else {

                // Desativa o servico do site
                $servico->update(["status" => ServicoEnum::INDISPONIVEL]);

                // Informacao no console
                $this->info("{$servico->nome} foi desativado - {$servico->canal_venda_id}");
            }
        }

        // Info sobre os servicos
        $this->info($servicos->count() . " servicos processados!");
    }

    /** Calcula os menores valores do destino e das categorias */
    private function menoresValorDestinoCategoria()
    {
        // Recupera os dados dos destinos
        $destinos = Destino::with([
            'categorias' => function ($q) {
                return $q->whereHas('servicosAtivos')->with([
                    'servicosAtivos' => function($filter) {
                        return $filter->where('valor_venda', '>', 10);
                    }
                ]);
            }
        ])->oldest()->get();

        // Percorre os destinos
        foreach ($destinos as $destino) {

            $valor_destino = 0;

            // Percorre as categorias do destino
            foreach ($destino->categorias as $categoria) {

                // Menor valor da categoria
                $menor_categoria = $categoria->servicosAtivos->min('valor_venda');

                // Menor valor do destino
                if($menor_categoria < $valor_destino || ($valor_destino == 0) && ($menor_categoria > 10)) {
                    $valor_destino = $menor_categoria;
                }

                // Atualiza o menor valor da categoria
                $categoria->update(['valor_minimo' => $menor_categoria]);
            }

            // Atualiza o menor valor do destino
            $destino->update(['valor_minimo' => $valor_destino]);

            $this->info("{$destino->nome} atualizado com sucesso - {$destino->canal_venda_id}");
        }
    }
}
