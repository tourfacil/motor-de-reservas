<?php namespace App\Jobs;

use App\Notifications\NovaCompraCliente;
use App\Notifications\NovaVendaFornecedor;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Notification;
use TourFacil\Core\Enum\IntegracaoEnum;
use TourFacil\Core\Models\Pedido;
use TourFacil\Core\Services\Exceedpark\ExceedService;
use TourFacil\Core\Services\Integracao\NovaXS\Olivas\OlivasService;
use TourFacil\Core\Services\Snowland\SnowlandService;
use Storage;
use TourFacil\Core\Services\Integracao\NovaXS\MiniMundo\MiniMundoService;

/**
 * Class NovaVendaJob
 * @package App\Jobs
 */
class NovaVendaJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Pedido
     */
    private $pedido;

    /**
     * @var array
     */
    private $vouchers = [];

    private $log_path;

    /**
     * Create a new job instance.
     *
     * @param Pedido $pedido
     */
    public function __construct(Pedido $pedido)
    {
        $this->pedido = $pedido;

        // Esta váriavel esta vindo da configuração em config/site.php
        $this->log_path = config('site.notificacao_email.path_log');
    }

    /**
     * Execute the job.
     *
     * @throws \Exception
     */
    public function handle()
    {
        // Recupera o pedido
        $this->pedido = Pedido::with([
            'cliente',
            'transacaoPedido',
            'reservas.fornecedor',
            'reservas.agendaDataServico',
            'reservas.servico.fotoPrincipal',
            'reservas.quantidadeReserva.variacaoServico',
            'reservas.dadoClienteReservaPedido.variacaoServico',
            'reservas.campoAdicionalReservaPedido.campoAdicionalServico',
        ])->find($this->pedido->id);

        // Envia um email para o fornecedor de cada reserva
        foreach ($this->pedido->reservas as $reserva) {

            // Envia notificação para o fornecedor sobre a reserva
            Notification::send($reserva->fornecedor, new NovaVendaFornecedor($this->pedido, $reserva));
            Storage::append($this->log_path, dataParaLog() . ' E-mail de fornecedor enviado - Pedido: #' . $this->pedido->codigo . ' - Reserva: #' . $reserva->voucher);

            // Verifica se o servico possui integracao
            if($reserva->servico->integracao == IntegracaoEnum::SNOWLAND) {
                // Gera o voucher do snowland
                (new SnowlandService($reserva))->gerarVoucherSnowland();
            }

            // Verifica se o servico possui integracao
            if($reserva->servico->integracao == IntegracaoEnum::EXCEED_PARK) {
                // Gera o voucher do exceed
                (new ExceedService($reserva))->gerarVoucherExceed();
            }

            // Verifica se o servico possui integracao
            if($reserva->servico->integracao == IntegracaoEnum::OLIVAS) {
                // Gera o voucher do Olivas
                (new OlivasService($reserva))->gerarVoucherOlivas();
            }

            // Verifica se o servico possui integracao
            if($reserva->servico->integracao == IntegracaoEnum::MINI_MUNDO) {
                // Gera o voucher do MiniMundo
                (new MiniMundoService($reserva))->gerarVoucherMiniMundo();
            }

            // Mailtrap tem limite de email por segundo
            if(env('APP_ENV') == "local") sleep(2);
        }

        // Envia email para o cliente
        Notification::send($this->pedido->cliente, new NovaCompraCliente($this->pedido));
    }
}
