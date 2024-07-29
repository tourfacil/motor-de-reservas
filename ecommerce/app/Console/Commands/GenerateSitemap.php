<?php namespace App\Console\Commands;

use Carbon\Carbon;
use File;
use Illuminate\Console\Command;
use TourFacil\Core\Enum\CategoriasEnum;
use TourFacil\Core\Enum\ServicoEnum;
use TourFacil\Core\Models\CanalVenda;
use TourFacil\Core\Models\Categoria;
use TourFacil\Core\Models\Destino;
use TourFacil\Core\Models\Servico;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

/**
 * Class GenerateSitemap
 * @package App\Console\Commands
 */
class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the sitemap.';

    /**
     * @var CanalVenda
     */
    private $canal_venda;

    /**
     * Array com o ID dos destinos ativos no site
     *
     * @var array
     */
    private $destinos_id = [];

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
     * @return void
     */
    public function handle()
    {
        $this->canal_venda = CanalVenda::find(env('CANAL_VENDA_ID'));
        $this->path = public_path('sitemap.xml');

        $status = $this->verifyfile();

        // Caso o arquivo tenha sido modificado a menos de 2 dias
        if($status == false) exit;

        $sitemap = Sitemap::create();

        $this->info("Iniciado sitemap");

        // Home page
        $sitemap->add(Url::create(route('ecommerce.index'))
                ->setLastModificationDate(Carbon::now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                ->setPriority(1.00));

        $this->info("Adicionado Home Page");

        // Rotas para central de ajuda
        $this->routesCentralAjuda($sitemap);

        // Rotas para o cliente
        $this->routesCliente($sitemap);

        // Rotas dos destinos
        $this->routesDestinos($sitemap);

        // Rotas das categorias
        $this->routesCategorias($sitemap);

        // Rota dos servicos
        $this->routesServico($sitemap);

        $sitemap->writeToFile(public_path("sitemap.xml"));

        $this->info("Sitemap Gerado");
    }

    /**
     * Verifica se é para atualizar o sitemap
     *
     * @return bool
     */
    private function verifyFile()
    {
        if(File::exists($this->path)) {
            $lastModified = Carbon::createFromTimestamp(File::lastModified($this->path));
            // Verifica se a ultima modificação foi a menos que 2 dias
            if(now()->diff($lastModified)->days < 2) {
                return false;
            }
        }
        return  true;
    }

    /**
     * Rotas para central de ajdua
     *
     * @param Sitemap $sitemap
     */
    private function routesCentralAjuda(Sitemap $sitemap)
    {
        $rotas_central = [
            route('ecommerce.ajuda.privacidade'),
            route('ecommerce.ajuda.cancelamento'),
            route('ecommerce.ajuda.termos'),
        ];

        // Central de ajuda
        foreach ($rotas_central as $url) {
            $sitemap->add(Url::create($url)
                ->setLastModificationDate(Carbon::now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                ->setPriority(0.60));
        }

        $this->info("Adicionado Central de Ajuda");
    }

    /**
     * Rotas para o cliente
     *
     * @param Sitemap $sitemap
     */
    private function routesCliente(Sitemap $sitemap)
    {
        $rotas = [
            route('ecommerce.cliente.login'),
        ];

        // Rotas cliente
        foreach ($rotas as $url) {
            $sitemap->add(Url::create($url)
                ->setLastModificationDate(Carbon::now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                ->setPriority(0.50));
        }

        $this->info("Adicionado Rotas Cliente");
    }

    /**
     * Adiciona as rotas dos destinos
     *
     * @param Sitemap $sitemap
     */
    private function routesDestinos(Sitemap $sitemap)
    {
        $destinos = Destino::whereHas('servicosAtivos')->where('canal_venda_id', $this->canal_venda->id)->get();

        foreach ($destinos as $destino) {

            // Salva o ID do destino para pegar as categorias e servicos
            $this->destinos_id[] = $destino->id;

            $sitemap->add(Url::create(route('ecommerce.destino.index', $destino->slug))
                ->setLastModificationDate(Carbon::now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                ->setPriority(0.80));

            $this->info("Adicionado Rota " . $destino->nome);
        }

        $this->info("Rotas dos destinos adicionados");
    }

    /**
     * Adiciona as rotas das categorias
     *
     * @param Sitemap $sitemap
     */
    private function routesCategorias(Sitemap $sitemap)
    {
        $categorias = Categoria::with('destino')
            ->whereHas('servicosAtivos')
            ->whereHas('destino.canalVenda', function ($q) {
                return $q->where('id', $this->canal_venda->id);
            })->whereIn('destino_id', $this->destinos_id)
            ->whereIn('tipo', [CategoriasEnum::NORMAL])->get();

        foreach ($categorias as $categoria) {
            $sitemap->add(Url::create(route('ecommerce.categoria.index', [ $categoria->destino->slug, $categoria->slug ]))
                ->setLastModificationDate(Carbon::now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                ->setPriority(0.80));

            $this->info("Adicionado Rota " . $categoria->nome);
        }

        $this->info("Rotas das Categorias adicionadas");
    }

    /**
     * Rotas dos serviços
     *
     * @param Sitemap $sitemap
     */
    public function routesServico(Sitemap $sitemap)
    {
        $servicos = Servico::with([
            'destino', 'categoria'
        ])->where([
            'canal_venda_id' => $this->canal_venda->id,
            'status' => ServicoEnum::ATIVO
        ])->whereIn('destino_id', $this->destinos_id)->get();

        $qtd = 0;

        foreach ($servicos as $servico) {
            $sitemap->add(Url::create(route('ecommerce.servico.view', [ $servico->destino->slug, $servico->categoria->slug, $servico->slug ]))
                ->setLastModificationDate(Carbon::now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                ->setPriority(0.90));

            $this->info("Rota " . $servico->nome);
            $qtd++;
        }

        $this->info("{$qtd} Rotas dos Servicos adicionadas");
    }
}
