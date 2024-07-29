<?php namespace App\Http\Controllers\Pedidos;

use App\Http\Requests\Reserva\UpdateCampoAdicionalReservaRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use TourFacil\Core\Models\CampoAdicionalReservaPedido;

/**
 * Class AdicionaisController
 * @package App\Http\Controllers\Pedidos
 */
class AdicionaisController extends Controller
{
    /**
     * Atualização dos dados adicionais da reserva
     *
     * @param UpdateCampoAdicionalReservaRequest $request
     * @return array
     */
    public function update(UpdateCampoAdicionalReservaRequest $request)
    {
        // Recupera os campos que é para serem atualizados
        $campos_adicionais = $request->get('adicionais');

        // Percorre cada campo e atualiza
        foreach ($campos_adicionais as $campo_adicional) {

            // recupera o campo da reserva
            $field = CampoAdicionalReservaPedido::find($campo_adicional['campo_adicional_id']);

            // Atualiza com a nova informação
            $field->update(['informacao' => $campo_adicional['informacao']]);
        }

        return $this->autoResponseJson(true, "Dados adicionais atualizados", "Os dados adicionais da reserva foram atualizados com sucesso!");
    }
}
