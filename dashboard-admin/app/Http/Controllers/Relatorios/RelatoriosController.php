<?php

namespace App\Http\Controllers\Relatorios;

use App\Exports\Terminais\RelatorioVendasTerminaisExport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use TourFacil\Core\Models\AgendaDataServico;
use TourFacil\Core\Models\ReservaPedido;
use TourFacil\Core\Models\Servico;

class RelatoriosController extends Controller
{
    public function relatorioDisponibilidade(Request $request)
    {
        $inicio = $request->get('data_inicial_venda');
        $final = $request->get('data_final_venda');

        $servicos = Servico::with('agendaServico.datasServico', 'fornecedor', 'destino')
            ->orderBy('id')
            ->where('status', 'ATIVO');

        if (!$inicio || !$final) {
            $servicos = $servicos->limit(0);
        }

        $inicio = Carbon::parse($inicio);
        $final = Carbon::parse($final);

        $servicos = $servicos->get();

        if ($request->get('tipo_relatorio') == 'XLS') {
            return (new RelatorioVendasTerminaisExport('paginas.relatorios.download.disponibilidade-xls', compact('servicos', 'inicio', 'final')))->download("Disponibilidade.xlsx");
        }

        return view('paginas.relatorios.disponibilidade', compact('servicos', 'inicio', 'final'));
    }

    public function relatorioReservaComDetalhes()
    {
        $reservas = ReservaPedido::with([
            'servico.fornecedor',
            'servico.destino',
            'servico.categorias',
            'pedido.cliente.endereco',
            'agendaDataServico',
        ])
            ->get();

        return (new RelatorioVendasTerminaisExport('paginas.relatorios.download.reserva-com-detalhes-xls', compact('reservas')))->download("Reservas.xlsx");
    }
}
