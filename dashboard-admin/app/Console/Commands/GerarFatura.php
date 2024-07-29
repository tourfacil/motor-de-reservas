<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use TourFacil\Core\Services\FaturaService;

class GerarFatura extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fatura:gerar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gera as faturas do Tour Fácil';

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
        $fatura_service = new FaturaService();
        $fatura_service->fecharFaturas();
    }
}
