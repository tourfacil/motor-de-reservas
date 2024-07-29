<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use TourFacil\Core\Enum\ServicoEnum;
use TourFacil\Core\Models\AgendaDataServico;
use TourFacil\Core\Models\CampoAdicionalServico;
use TourFacil\Core\Models\Servico;
use TourFacil\Core\Models\VariacaoServico;
use TourFacil\Core\Services\UploadPhotoService;

class ExcluirServicos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'servicos:excluir';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Exclui serviços não utilizados';

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
     * @return mixed
     */
    public function handle()
    {
        $servicos_id = [3, 31, 39, 54, 64, 68, 77, 79, 80, 86, 87, 88, 95, 110, 111, 112, 113, 114, 122, 130, 131, 132, 137];

        $servicos = Servico::with([
            'agendaServico',
            'categorias',
            'secoesCategoria',
            'camposAdicionais',
            'fotos',
            'variacaoServico',
        ])->doesntHave('reservas')
            ->whereIn('status', [ServicoEnum::INATIVO, ServicoEnum::PENDENTE])
            ->whereIn('id', $servicos_id)->get();

        $this->info($servicos->count() . " servicos a serem excluidos");

        foreach ($servicos as $servico) {

            // Verifica se possui agenda
            if(is_object($servico->agenda)) {
                // Exclui as datas do servico
                AgendaDataServico::where('agenda_servico_id', $servico->agenda->id)->delete();
                // Exlui a agenda do servico
                $servico->agenda->delete();
            };

            // Remove a categoria do servico
            $categorias_id = $servico->categorias->pluck('id')->toArray();
            $secoes_categoria = $servico->secoesCategoria->pluck('id')->toArray();

            // Remove a categoria do servico
            $servico->categorias()->detach($categorias_id);
            $servico->secoesCategoria()->detach($secoes_categoria);

            // Deleta os campos adicionais
            $campos_id = $servico->camposAdicionais->pluck('id')->toArray();
            CampoAdicionalServico::whereIn('id', $campos_id)->forceDelete();

            // Deleta as fotos do S3 e o DB
            foreach ($servico->fotos as $foto) {
                $foto->delete();
                if(env('APP_ENV') == "production") {
                    UploadPhotoService::delete(array_values($foto->foto));
                }
            }

            // Deleta as variacoes do servico
            $variacoes_id = $servico->variacaoServico->pluck('id')->toArray();
            VariacaoServico::whereIn('id', $variacoes_id)->forceDelete();

            // Deleta o servico
            $servico->forceDelete();

            $this->info($servico->nome . " excluido com sucesso!");
        }
    }
}
