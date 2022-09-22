<?php namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use PDF;
use TourFacil\Core\Enum\IntegracaoEnum;
use TourFacil\Core\Models\ExceedReservaPedido;
use TourFacil\Core\Models\MiniMundoReservaPedido;
use TourFacil\Core\Models\Pedido;
use TourFacil\Core\Models\SnowlandReservaPedido;
use TourFacil\Core\Models\OlivasReservaPedido;

/**
 * Class NovaCompraCliente
 * @package App\Notifications
 */
class NovaCompraCliente extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @var Pedido
     */
    private $pedido;

    /**
     * @var array
     */
    private $vouchers;

    /**
     * Create a new notification instance.
     *
     * @param Pedido $pedido
     */
    public function __construct(Pedido $pedido)
    {
        $this->pedido = $pedido;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        // Vouchers do cliente
        $this->createVouchers();

        // Prepara o email
        $mail = (new MailMessage)->view('emails.nova-compra-cliente', [
            'cliente' => $notifiable,
            'pedido' => $this->pedido,
        ]);

        // Titulo do email
        $mail->subject("Recebemos seu pedido #{$this->pedido->codigo} - TourFácil.com.br");

        // Caso tiver o voucher envia junto
        foreach ($this->vouchers as $voucher) {
            // Coloca o voucher como anexo
            $mail->attachData($voucher['voucher'], $voucher['name'], $voucher['options']);
        }

        return $mail;
    }

    /**
     * Prepara os vouchers para ser enviados via email
     */
    private function createVouchers()
    {
        // Envia um email para o fornecedor de cada reserva
        foreach ($this->pedido->reservas as $reserva) {
            // Verifica se o servico possui integracao
            if($reserva->servico->integracao == IntegracaoEnum::SNOWLAND) {
                // Coloca o voucher do snowland para email
                $this->appendVoucherSnowland($reserva);
            } elseif($reserva->servico->integracao == IntegracaoEnum::EXCEED_PARK) {
                // Coloca o voucher do exceed park para email
                $this->appendVoucherExceed($reserva);
            } elseif($reserva->servico->integracao == IntegracaoEnum::OLIVAS) {
                // Coloca o voucher do olivas para emails
                $this->appendVoucherOlivas($reserva);
            } elseif($reserva->servico->integracao == IntegracaoEnum::MINI_MUNDO) {
                // Coloca o voucher do olivas para emails
                $this->appendVoucherMiniMundo($reserva);
            } else {
                // Gera o PDF da reserva
                $this->generateTempVoucher($reserva, $this->pedido->cliente);
            }
        }
    }

    /**
     * Gera o voucher de cada reserva
     *
     * @param $reserva
     * @param $cliente
     */
    private function generateTempVoucher($reserva, $cliente)
    {
        // PDF com o voucher
        $pdf = PDF::loadView('voucher.voucher', [
            'reserva' => $reserva,
            'cliente' => $cliente,
            'inline_pdf' => true,
        ]);

        // Configura o Zoom
        $pdf->setOption('zoom', 1.8);

        // Salva o PDF temporario para enviar no email
        $this->vouchers[] = [
            'voucher' => $pdf->output(),
            'name' => "{$reserva->servico->nome} #{$reserva->voucher}.pdf",
            'options' => ['mime' => 'application/pdf']
        ];
    }

    /**
     * Voucher do snowland para email
     *
     * @param $reserva
     */
    private function appendVoucherSnowland($reserva)
    {
        // Recupera o voucher
        $voucher = SnowlandReservaPedido::where('reserva_pedido_id', $reserva->id)->first();

        // Pega os dados do voucher
        $voucher_output = file_get_contents($voucher->voucher_as_byte);

        // Salva o PDF temporario para enviar no email
        $this->vouchers[] = [
            'voucher' => $voucher_output,
            'name' => "Parque Snowland #{$reserva->voucher}.pdf",
            'options' => ['mime' => 'application/pdf']
        ];
    }

    /**
     * Voucher do exceed park para email
     *
     * @param $reserva
     */
    private function appendVoucherExceed($reserva)
    {
        // Recupera o voucher
        $voucher = ExceedReservaPedido::where('reserva_pedido_id', $reserva->id)->first();

        // Pega os dados do voucher
        $voucher_output = file_get_contents($voucher->voucher_as_byte);

        // Salva o PDF temporario para enviar no email
        $this->vouchers[] = [
            'voucher' => $voucher_output,
            'name' => "Exceed Park #{$reserva->voucher}.pdf",
            'options' => ['mime' => 'application/pdf']
        ];
    }

        /**
     * Voucher do Olivas para email
     *
     * @param $reserva
     */
    private function appendVoucherOlivas($reserva)
    {
        // Recupera o voucher
        $voucher = OlivasReservaPedido::where('reserva_pedido_id', $reserva->id)->first();

        // Pega os dados do voucher
        $voucher_output = file_get_contents($voucher->voucher_as_byte);

        // Salva o PDF temporario para enviar no email
        $this->vouchers[] = [
            'voucher' => $voucher_output,
            'name' => "Olivas #{$reserva->voucher}.pdf",
            'options' => ['mime' => 'application/pdf']
        ];
    }

            /**
     * Voucher do MiniMundo para email
     *
     * @param $reserva
     */
    private function appendVoucherMiniMundo($reserva)
    {
        // Recupera o voucher
        $voucher = MiniMundoReservaPedido::where('reserva_pedido_id', $reserva->id)->first();

        // Pega os dados do voucher
        $voucher_output = file_get_contents($voucher->voucher_as_byte);

        // Salva o PDF temporario para enviar no email
        $this->vouchers[] = [
            'voucher' => $voucher_output,
            'name' => "Mini Mundo #{$reserva->voucher}.pdf",
            'options' => ['mime' => 'application/pdf']
        ];
    }
}
