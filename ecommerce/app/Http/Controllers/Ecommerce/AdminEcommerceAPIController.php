<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use TourFacil\Core\Models\Pedido;
use App\Jobs\NovaVendaJob;

class AdminEcommerceAPIController extends Controller
{
    public function solicitarEnvioDeEmailAposVendaInterna(Request $request) {

        $key_code = $request->get('key_code');

        if($key_code != config('site.admin_ecommerce_api.key_code')) {
            return response([
                'info' => 'Acesso negado',
            ], 200);
        }

        $pedido_id = $request->get('data')['pedido_id'];

        $pedido = Pedido::find($pedido_id);

        if($pedido != null) {
            // Dispara o job de nova compra
            NovaVendaJob::dispatch($pedido);
        }

        $response = [
            'info' => 'Sucesso',
            'text' => "O pedido $pedido->id, foi colocado na fila para disparo de e-mails",
        ];

        return response($response, 200);
    }
}
