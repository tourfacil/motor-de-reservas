<?php namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

/**
 * Class NewUserTerminaisNotification
 * @package App\Notifications
 */
class NewUserTerminaisNotification extends Notification
{
    use Queueable;

    /**
     * @var
     */
    private $password;

    /**
     * Create a new notification instance.
     *
     * NewUserTerminaisNotification constructor.
     * @param $password
     */
    public function __construct($password)
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
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)->view(
            'emails.terminais.novo-usuario-terminal', [
            'user' => $notifiable,
            'password' => $this->password
        ])->subject('Seja bem vindo | Terminais CDI');
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
