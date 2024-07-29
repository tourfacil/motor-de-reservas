<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use TourFacil\Core\Models\Pedido;

class AvaliacaoServicoMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $pedido;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Pedido $pedido)
    {
        $this->pedido = $pedido;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $dados = [
            'pedido' => $this->pedido,
            'cliente' => $this->pedido->cliente
        ];

        return $this->subject("Avaliação de pedido - Tour Fácil")->view('emails.avaliacao-servico-mail', $dados);
    }
}
