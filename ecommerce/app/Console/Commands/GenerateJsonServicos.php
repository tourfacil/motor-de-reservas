<?php namespace App\Console\Commands;

use File;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use TourFacil\Core\Enum\ServicoEnum;
use TourFacil\Core\Models\CanalVenda;
use TourFacil\Core\Models\Servico;
use Str;

/**
 * Class GenerateJsonServicos
 * @package App\Console\Commands
 */
class GenerateJsonServicos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'servicos:json';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gera um JSON com os serviços do site usado para pesquisa no site';

    /**
     * @var CanalVenda
     */
    private $canal_venda;

    /**
     * @var string
     */
    private $path;

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
        // Recupera o canal de venda
        $this->canal_venda = CanalVenda::find(env('CANAL_VENDA_ID'));
        $this->path = public_path('search.json');

        $json = [];
        $destinos = [];

        // Recupera os servicos do site
        $servicos = Servico::with([
            'destino',
            'categoria',
            'categorias',
            'secoesCategoria',
            'ranking',
            'fotoPrincipal'
        ])->whereHas('categoria', function ($q) {
            return $q->whereNull('deleted_at');
        })->whereHas('destino', function ($q) {
            return $q->whereNull('deleted_at');
        })->where([
            'canal_venda_id' => $this->canal_venda->id,
            'status' => ServicoEnum::ATIVO
        ])->get([
            'id', 'slug', 'nome', 'valor_venda', 'cidade', 'destino_id',
            'descricao_curta', 'palavras_chaves'
        ]);

        // Percorre os servicos
        foreach ($servicos as $servico) {

            $keys = [];
            $categorias = $servico->categorias->pluck('nome')->toArray();
            $secoes = $servico->secoesCategoria->pluck('nome')->toArray();

            // Salva os dados dos destinos
            $destinos[$servico->destino->id] = [
                'nome' => $servico->destino->nome,
                'valor_venda' => formataValor($servico->destino->valor_minimo),
                'palavras_chaves' => mb_strtolower(tirarAcentos($servico->destino->nome)),
                'ranking' => 999,
                'cidade' => "Em destaque",
                'image' => $servico->destino->foto_destino,
                'url' => "{$servico->destino->slug}"
            ];

            $index = [
                explode(",", $servico->palavras_chaves),
                $categorias, $secoes, [$servico->cidade]
            ];

            // Remove alguns caracteres do nome do servico
            $nome_servico = mb_strtolower(tirarAcentos($servico->nome));
            $nome_servico = str_replace("-", "", $nome_servico);
            $nome_servico = str_replace("/", "", $nome_servico);
            $nome_servico = str_replace("+", "", $nome_servico);
            $nome_servico = str_replace(",", "", $nome_servico);
            $nome_servico = trim($nome_servico);

            // Salva o nome e categoria destino na lista das keywords
            $keys['nome'.$servico->id] = $nome_servico;
            $keys['categoria'.$servico->id] = mb_strtolower(tirarAcentos($servico->categoria->nome)) . " em " . mb_strtolower(tirarAcentos($servico->destino->nome));

            // Monta a lista de keywords removendo o maximo de palavras duplicadas
            foreach ($index as $index_servico) {
                foreach ($index_servico as $keywords) {
                    $keywords = explode(" ", $keywords);
                    if(is_array($keywords)) {
                        foreach ($keywords as $keyword) {
                            if(strlen($keyword) > 2 && (! Str::contains($nome_servico, $keyword))) {
                                $keyword = mb_strtolower(tirarAcentos($keyword));
                                $keys[$keyword] = $keyword;
                            }
                        }
                    } else {
                        $keyword = mb_strtolower(tirarAcentos($keywords));
                        if(!Str::contains($nome_servico, $keyword)) $keys[$keyword] = $keyword;
                    }
                }
            }

            $json[] = [
                'nome' => $servico->nome,
                'valor_venda' => formataValor($servico->valor_venda),
                'palavras_chaves' => implode(" ", $keys),
                'ranking' => $servico->ranking->ranking ?? 0,
                'cidade' => $servico->cidade,
                'image' => $servico->fotoPrincipal->foto_large,
                'url' => "{$servico->destino->slug}/{$servico->categoria->slug}/{$servico->slug}"
            ];
        }

        // Remove as indices
        $destinos = array_values($destinos);

        // Mescla os dois arrays
        $json = array_merge($json, $destinos);

        // Salva o arquivo na public em formato de JSON
        File::put($this->path, json_encode($json));

        $this->info("Arquivo salvo em: " . $this->path);
    }
}
