<?php namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use TourFacil\Core\Enum\ComissaoStatus;
use TourFacil\Core\Enum\TerminaisEnum;
use TourFacil\Core\Models\PagamentoTerminal;
use TourFacil\Core\Models\Terminal;

/**
 * Class ComissaoMensalTerminal
 * @package App\Console\Commands
 */
class ComissaoMensalTerminal extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'terminais:comissao-mensal {mes?} {ano?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gera as comissoes mensais dos terminais';

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
        $dados_terminais = []; $created = [];
        $dia_pagamento = TerminaisEnum::DIA_PAGAMENTO;

        // Recupera o mes por argumento ou pega o mes atual
        $mes = $this->argument('mes') ?? date('m');

        // Recupera o mes por argumento ou pega o atual
        $ano = $this->argument('ano') ?? date('Y');

        // Coloca um zero a esquerda
        if(! Str::contains($mes,"0") && strlen($mes) < 2) {
            $mes = "0{$mes}";
        }

        // O ano deve ter 4 caracteres
        if(strlen($ano) < 4) {
            $this->error("O ano deve ter 4 caracters. Ex: " . date('Y'));
            return;
        }

        // Mes de pagameto
        $mes_pagamento = Carbon::createFromFormat("d/m/Y", "$dia_pagamento/$mes/$ano")->startOfDay();

        // Inicio do mes de referencia
        $referencia_inicio = $mes_pagamento->copy()->subMonth(TerminaisEnum::MES_PAGAMENTO)->startOfMonth();

        // Final do mes de referencia
        $referencia_final = $referencia_inicio->copy()->endOfMonth();

        // Lista dos terminais com as vendas
        $venda_terminais = Terminal::with([
            'comissaoTerminal' => function($q) use ($referencia_inicio, $referencia_final) {
                return $q->where('status', ComissaoStatus::AGUARDANDO)
                    ->whereBetween('created_at', [$referencia_inicio, $referencia_final])->with('reservaPedido');
            }
        ])->get();

        // Percorre os terminais
        foreach ($venda_terminais as $terminal) {
            // Somente os terminais que tiveram vendas
            if($terminal->comissaoTerminal->count()) {
                // Cria um array para cada terminal
                $dados_terminais[$terminal->id] = [
                    'terminal_id' => $terminal->id, 'total_comissao' => 0, 'terminal' => $terminal->nome,
                    'total_vendido' => 0, 'quantidade_ingressos' => 0, 'comissoes' => []
                ];
                // Percorre cada venda do terminal
                foreach ($terminal->comissaoTerminal as $comissao) {
                    $dados_terminais[$terminal->id]['total_comissao'] += $comissao->comissao;
                    $dados_terminais[$terminal->id]['total_vendido'] += $comissao->reservaPedido->valor_total;
                    $dados_terminais[$terminal->id]['quantidade_ingressos'] += $comissao->reservaPedido->quantidade;
                    $dados_terminais[$terminal->id]['comissoes'][] = $comissao->id;
                }
            }
        }

        $total_comissao = 0;

        // Insere os pagamentos para os terminais
        foreach ($dados_terminais as $pagamento_terminal) {

            // Total de comissao paga
            $total_comissao += $pagamento_terminal['total_comissao'];

            // Cria um pagamento do terminal
            $pagamento = PagamentoTerminal::create([
                "terminal_id" => $pagamento_terminal['terminal_id'],
                "mes_referencia" => $referencia_inicio,
                "mes_pagamento" => $mes_pagamento,
                "total_comissao" => $pagamento_terminal['total_comissao'],
            ]);

            // Sincroniza as comissoes com o pagamento
            $created[] = $pagamento->comissoesPagamento()->attach($pagamento_terminal['comissoes']);
        }

        $this->info(sizeof($created) . " Comissoes geradas. Total R$ " . $total_comissao);
    }
}
