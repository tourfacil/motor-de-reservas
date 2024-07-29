<?php

namespace App\Http\Controllers\Relatorios;

use App\Exports\Terminais\RelatorioVendasTerminaisExport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use TourFacil\Core\Models\ReservaPedido;

class MalaProntaController extends Controller
{
    public function index(Request $request)
    {
        $reservas = ReservaPedido::with([
            'pedido',
            'servico',
            'agendaDataServico',
            'vendedor'
        ])
        ->where('created_at', '>=', '2022-08-20')
        ->whereHas('servico', function($servico) {
            $servico->where('destino_id', 1);
        })
        ->whereNull('afiliado_id')
        ->whereIn('status', ['ATIVA', 'UTILIZADO'])
        ->get();

        $reservas = $reservas->sortBy('id');

        return (new RelatorioVendasTerminaisExport('paginas.relatorios.download.mala-pronta-xls', compact('reservas')))->download("Mala-Pronta.xlsx"); 
    }
}
