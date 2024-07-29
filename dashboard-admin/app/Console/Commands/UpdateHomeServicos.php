<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use TourFacil\Core\Enum\TipoHomeDestinoEnum;
use TourFacil\Core\Models\HomeDestino;
use TourFacil\Core\Models\RankingServico;
use TourFacil\Core\Models\Servico;

/**
 * Class UpdateHomeServicos
 * @package App\Console\Commands
 */
class UpdateHomeServicos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'servicos:home {tipo=cadastrados}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Atualiza os serviços na home destino';

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
        // Tipo da secao que deve ser atualizada
        $tipo = ($this->argument('tipo') == "cadastrados"
            ? TipoHomeDestinoEnum::ULTIMOS_CADASTRADOS : TipoHomeDestinoEnum::MAIS_VENDIDOS);

        // Secoes na home destino
        $secoes = HomeDestino::with([
            'servicos' => function($q) {
                return $q->select(['servicos.id', 'servicos.destino_id', 'servicos.nome']);
            }
        ])->where('tipo', $tipo)->get();

        if($tipo == TipoHomeDestinoEnum::MAIS_VENDIDOS) {
            $this->updateMaisVendidos($secoes);
        } else {
            $this->updateUltimosCadastrados($secoes);
        }
    }

    /**
     * Atualiza as home secao com servicos mais vendidos
     *
     * @param $secoes
     */
    private function updateMaisVendidos($secoes)
    {
        // Percorre cada secao
        foreach ($secoes as $secao) {

            // Recupera os servicos mais vendidos do destino
            $mais_vendidos = RankingServico::with([
                'servico' => function($q) use ($secao) {
                    return $q->where('servicos.destino_id', $secao->destino_id)->select([
                        'id', 'destino_id', 'nome'
                    ]);
                }
            ])->whereHas('servico', function($q) use ($secao) {
                return $q->where('servicos.destino_id', $secao->destino_id);
            })->orderBy('ranking', 'DESC')->limit(10)->get();

            // Caso possua mais que 4 servicos mais vendidos
            if($mais_vendidos->count() >= 4) {

                $novos_servicos = [];

                // Monta o array com os novos servicos
                foreach ($mais_vendidos as $index => $servico) {
                    $novos_servicos[] = [
                        'servico_id' => $servico->servico_id,
                        'ordem' => $index + 1
                    ];
                }

                // Atualiza os servicos mais vendidos da secao
                $secao->servicos()->sync($novos_servicos);

                $this->info("A secao {$secao->titulo} foi atualizada com os serviços mais vendidos!");
            }
        }
    }

    /**
     * Atualiza a home destino com os ultimos servicos cadastrados
     *
     * @param $secoes
     */
    private function updateUltimosCadastrados($secoes)
    {
        // Percorre cada secao
        foreach ($secoes as $secao) {

            // Recupera os ultimos servicos cadastrados
            $ultimos_cadastrados = Servico::where('destino_id', $secao->destino_id)
                ->latest()->limit(10)->get();

            // ID dos servicos cadastrados
            $servicos_atuais = $secao->servicos->pluck('id')->toArray();

            // Caso possua mais que 4 servicos mais vendidos
            if($ultimos_cadastrados->count() >= 4) {

                // Array com os novos servicos
                $novos_servicos = [];

                // Monta o array com os novos servicos
                foreach ($ultimos_cadastrados as $index => $servico) {

                    // Recupera o id do servico na mesma posicao que o atual
                    $id_cadastrado = $servicos_atuais[$index] ?? null;

                    // Caso os ids sejam diferentes monta o array
                    if($id_cadastrado != $servico->id) {
                        $novos_servicos[] = [
                            'servico_id' => $servico->id,
                            'ordem' => $index + 1
                        ];
                    }
                }

                // Caso foi cadastrado algum servico novo
                if(count($novos_servicos)) {

                    // Atualiza os servicos por ordem de cadastro
                    $secao->servicos()->sync($novos_servicos);

                    $this->info("A secao {$secao->titulo} foi atualizada com os ultimos serviços cadastrados!");
                }
            }
        }
    }
}
