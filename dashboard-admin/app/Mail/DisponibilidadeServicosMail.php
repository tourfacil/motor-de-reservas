<?php namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Class DisponibilidadeServicosMail
 * @package App\Mail
 */
class DisponibilidadeServicosMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var array
     */
    private $servicos;

    /**
     * Create a new message instance.
     *
     * @param array $servicos
     */
    public function __construct(array $servicos)
    {
        $this->servicos = $servicos;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.aviso-disponibilidade', [
            'servicos' => $this->servicos
        ])->subject('Aviso sobre disponibilidade - TourFácil');
    }
}
