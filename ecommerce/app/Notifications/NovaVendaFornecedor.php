<?php namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use TourFacil\Core\Models\Pedido;
use TourFacil\Core\Models\ReservaPedido;

/**
 * Class NovaVendaFornecedor
 * @package App\Notifications
 */
class NovaVendaFornecedor extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @var Pedido
     */
    private $pedido;

    /**
     * @var ReservaPedido
     */
    private $reserva;

    /**
     * Create a new notification instance.
     *
     * @param Pedido $pedido
     * @param ReservaPedido $reserva
     */
    public function __construct(Pedido $pedido, ReservaPedido $reserva)
    {
        $this->pedido = $pedido;
        $this->reserva = $reserva;
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
     * @param $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        $mail = (new MailMessage)->view('emails.nova-venda-fornecedor', [
            'fornecedor' => $notifiable,
            'pedido' => $this->pedido,
            'reserva' => $this->reserva
        ]);

        $mail->subject("Nova venda realizada #{$this->reserva->voucher} - TourFácil.com.br");

        $mail->salutation("Nova venda #{$this->reserva->voucher}");

        return $mail;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
