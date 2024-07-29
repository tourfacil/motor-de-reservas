<?php namespace App\Console\Commands;

use App\Mail\DisponibilidadeServicosMail;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Mail;
use TourFacil\Core\Enum\AgendaEnum;
use TourFacil\Core\Enum\EmailEnum;
use TourFacil\Core\Enum\ServicoEnum;
use TourFacil\Core\Models\AgendaDataServico;
use TourFacil\Core\Models\Servico;

/**
 * Class VerificaDisponibilidade
 * @package App\Console\Commands
 */
class VerificaDisponibilidade extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'servicos:disponibilidade';

    /**
     * Quantidade de dias com disponibildiade minima
     *
     * @var int
     */
    protected $min_quantity = 15;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifica a disponibilidade dos serviços';

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
        // Datas para pesquisa
        $start = Carbon::today();
        $end = $start->copy()->addMonths(6)->endOfDay();
        $info_servicos = [];

        // Recupera os servicos ativos
        $servicos = Servico::with('agendaServico', 'canalVenda')
            ->whereIn("status", [ServicoEnum::ATIVO])->get([
                'id', 'nome', 'canal_venda_id'
            ]);

        // Percorre os serviços
        foreach($servicos as $servico) {

            // Dias com disponibilidade baixa
            $info_servicos[$servico->id] = [
                'servico' => $servico->nome,
                'canal_venda' => $servico->canalVenda->nome,
                'agenda_id' => $servico->agenda->id,
                'minimo_disponivel' => true, // Se o servico possui os 15 dias de venda
                'necessita_verificacao' => false, // Condicao se manda email ou nao
                'disponibilidade_baixa' => [], // Dias com baixa disponibilidade
                'ultimo_data' => '', // Ultima data cadastrada
            ];

            // Menor valor da agenda
            $agenda = AgendaDataServico::where([
                'agenda_servico_id' => $servico->agenda->id,
                'status' => AgendaEnum::ATIVO
            ])->where('disponivel', '>=', 1)->whereBetween('data', [$start, $end])->get();

            // Quantidade de dias para venda
            $qtd_agenda = $agenda->count();

            // Conta quantos dias estão disponiveis
            foreach ($agenda as $index => $data_agenda) {

                // Quantidade minima informada na agenda
                if($data_agenda->disponivel <= $servico->agenda->disponibilidade_minima) {
                    $info_servicos[$servico->id]['disponibilidade_baixa'][] = [
                        'data' => $data_agenda->data->format('d/m/Y'),
                        'disponivel' => $data_agenda->disponivel
                    ];
                }

                // Ultima data lancada
                if(($index + 1) == $qtd_agenda) {

                    // Verifica se o servico possui o minimo de dias para venda
                    if($data_agenda->data->diff($start)->days <= $this->min_quantity) {
                        $info_servicos[$servico->id]['minimo_disponivel'] = false;
                        $info_servicos[$servico->id]['necessita_verificacao'] = true;
                    }

                    // Salva a ultima data cadastrada
                    $info_servicos[$servico->id]['ultimo_data'] = $data_agenda->data->format('d/m/Y');
                }
            }

            // Caso haja dias com disponibilidade baixa
            if(count($info_servicos[$servico->id]['disponibilidade_baixa'])) {
                $info_servicos[$servico->id]['necessita_verificacao'] = true;
            }
        }

        // Deixa somente os servicos que precisa de verificacao
        $info_servicos = array_filter($info_servicos, function ($servico) {
           return ($servico['necessita_verificacao']);
        });

        // Envia email de aviso com os servicos
        if(count($info_servicos)) {
            Mail::to(EmailEnum::EMAILS_AVISO)
                ->send(new DisponibilidadeServicosMail($info_servicos));
        }
    }
}
