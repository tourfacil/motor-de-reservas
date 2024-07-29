<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use TourFacil\Core\Enum\MetodoPagamentoEnum;
use TourFacil\Core\Enum\StatusPagamentoEnum;
use TourFacil\Core\Enum\StatusPedidoEnum;
use TourFacil\Core\Enum\StatusPixEnum;
use TourFacil\Core\Models\Pedido;
use TourFacil\Core\Services\AdminEcommerceAPI\AdminEcommerceAPI;
use TourFacil\Core\Services\FinalizacaoService;
use TourFacil\Core\Services\Pagamento\PixService;

class AtualizarPixPendentes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pix:atualizar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Atualiza a situação dos PIX pendentes';

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
        
        $pedidos = Pedido::where('status_pagamento', StatusPagamentoEnum::PENDENTE)
        ->where('metodo_pagamento', MetodoPagamentoEnum::PIX)
        ->where('status', StatusPedidoEnum::AGUARDANDO)
        ->get();

        $pendentes = 0;
        $pagos = 0;
        $expirados = 0;

        foreach($pedidos as $pedido) {

            $status = PixService::getAndUpdateSituacaoPix($pedido);

            if($status == StatusPixEnum::PAGO) {
                $pagos++;

                // Verifica se o pedido ja esta finalizado
                // Caso não esteja, ele não envia os e-mails
                // Caso esteja, ele envia os e-mails para cliente e fornecedor
                // Caso for encontrada uma reserva não finalizada ele marca ela com uma FLAG
                if(FinalizacaoService::isPedidoFinalizado($pedido)) {
                    AdminEcommerceAPI::solicitarEnvioDeEmailAposVendaInterna($pedido);
                }
            }

            if($status == StatusPixEnum::EXPIRADO) {
                $expirados++;
            }

            if($status == StatusPixEnum::PENDENTE) {
                $pendentes++;
            }
        }

        echo "Pedidos que continuam pendentes: $pendentes\n";
        echo "Pedidos que expiraram: $expirados\n";
        echo "Pedidos que pagos: $pagos\n";

    }
}
