<?php namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

/**
 * Class NovoClienteNotification
 * @package App\Notifications
 */
class NovoClienteNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @var string
     */
    private $password;

    /**
     * Create a new notification instance.
     *
     * @param $password
     */
    public function __construct(string $password)
    {
        $this->password = $password;
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
        $mail = (new MailMessage)->view(
            'emails.novo-cliente', [
            'cliente' => $notifiable,
            'password' => $this->password
        ]);

        $mail->subject('Seja muito bem vindo - TourFácil.com.br');

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
