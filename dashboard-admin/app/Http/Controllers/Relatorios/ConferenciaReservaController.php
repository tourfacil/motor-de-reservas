<?php

namespace App\Http\Controllers\Relatorios;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use TourFacil\Core\Models\ConferenciaReserva;

class ConferenciaReservaController extends Controller
{
    public function atualizarConferencia(Request $request) {

        // Busca se ja existe uma conferencia para esta reserva
        $conferencia_reserva = ConferenciaReserva::where('reserva_pedido_id', $request->get('reserva_pedido_id'))->get()->first();

        // Caso não exista ele cria uma ja com os dados informados
        // Se existir ele apenas atualiza os dados
        if($conferencia_reserva == null) {
            ConferenciaReserva::create($request->all());
        } else {
            $conferencia_reserva->update($request->all());
        }

        return response(['status' => true], 200);
    }
}
