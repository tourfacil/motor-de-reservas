<?php

namespace App\Console\Commands;

use GuzzleHttp\Client;
use Illuminate\Console\Command;
use TourFacil\Core\Enum\CanaisVendaEnum;
use TourFacil\Core\Models\CanalVenda;

class ResetCacheCanalVenda extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'canal-venda:reset-cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Limpa o cache dos canais de venda';

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
        $canais_venda = CanalVenda::whereIn('id', [
            CanaisVendaEnum::TOURFACIL
        ])->get();

        // Percorre os canais de venda
        foreach ($canais_venda as $canal_venda) {

            // Monta a URL de limpar o cache
            $url_reset_cache = $canal_venda->site . CanaisVendaEnum::URL_CACHE_CLEAR;

            // Efetua um GET na URL para limpar o cache
            $res = (new Client(['verify' => false]))->get($url_reset_cache);

            $this->info($canal_venda->nome . " response: " . $res->getBody()->getContents());
        }
    }
}
