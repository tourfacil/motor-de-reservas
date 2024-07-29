<?php

namespace App\Http\Controllers\Painel\Relatorios;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use TourFacil\Core\Enum\StatusReservaEnum;
use TourFacil\Core\Models\ReservaPedido;

class HomelistController extends Controller
{
    public function index(Request $request) {

        $inicio = $request->get('inicio');
        $final = $request->get('final');

        if($inicio == null || $final == null) {
            $inicio = Carbon::now()->firstOfMonth();
            $final = Carbon::now()->lastOfMonth();
        } else {
            $inicio = Carbon::createFromFormat('d-m-Y', $inicio);
            $final = Carbon::createFromFormat('d-m-Y', $final);
        }

        $reservas = ReservaPedido::where('fornecedor_id', auth()->user()->fornecedor_id)
            ->whereHas('agendaDataServico', function($query) use ($inicio, $final) {
                $query->whereDate('data', '>=', $inicio);
                $query->whereDate('data', '<=', $final);
            })  
            ->whereIn('status', [
                StatusReservaEnum::ATIVA,
                StatusReservaEnum::UTILIZADO,
                StatusReservaEnum::FINALIZAR,
            ])
            ->with([
                'agendaDataServico',
                'pedido', 
                'fornecedor', 
                'pedido.cliente', 
                'servico', 
                'afiliado', 
                'conferenciaReserva'
            ])
            ->get();

        $dados = [
            'inicio' => $inicio,
            'final' => $final,
            'reservas' => $reservas,
        ];

        return view('paginas.relatorios.homelist', $dados);
    }
}
