<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Storage;
use TourFacil\Core\Models\ReservaPedido;
use Carbon\Carbon;

class RelatorioSkyGlass extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'relatorio:skyglass';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Relatório do Skyglass';

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

        $data = Carbon::now()->format('d/m/Y H:i');
        $this->log("Relatório realizado em: " . $data);


        $reservas = ReservaPedido::where('servico_id', 107)
        ->whereHas('agendaDataServico', function($agenda) {

            $data_inicio = Carbon::parse('2022-12-01');
            $data_final = Carbon::parse('2023-01-01');

            $agenda->where('data', '>=', $data_inicio);
            $agenda->where('data', '<=', $data_final);

        })
        ->whereHas('integracaoPWI')
        ->where('status', 'ATIVA')
        ->get();

        foreach ($reservas as $reserva) {

            if(isset(json_decode($reserva->integracaoPWI->dados, true)['data']) == false) {
                continue;
            }

            $texto = "";

            $texto .= "ID TourFacil: {$reserva->voucher} - ";
            $texto .="ID Skyglass: " . json_decode($reserva->integracaoPWI->dados, true)['data']['id'] . ' - ';
            $texto .= "Utilização: " . $reserva->agendaDataServico->data->format('d/m/Y');

            $this->log($texto);
        }
    }

    public function log($text) {
        Storage::append('skyglass.txt', $text);
    }
}
