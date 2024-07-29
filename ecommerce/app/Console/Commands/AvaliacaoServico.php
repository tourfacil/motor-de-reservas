<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use TourFacil\Core\Services\AvaliacaoService;

class AvaliacaoServico extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'servicos:avaliacao-email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispara e-mails para clientes de pedidos aptos ao recebimento de avaliação';

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
        $avaliacao_service = new AvaliacaoService();
        $avaliacao_service->dispararEmailsAvaliacao();
    }
}
