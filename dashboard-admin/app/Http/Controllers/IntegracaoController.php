<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use TourFacil\Core\Enum\IntegracaoEnum;
use TourFacil\Core\Models\MatriaReservaPedido;
use TourFacil\Core\Models\ReservaPedido;
use TourFacil\Core\Services\Integracao\NovaXS\AcquaMotion\AcquaMotionService;
use TourFacil\Core\Services\Integracao\NovaXS\Alpen\AlpenService;
use TourFacil\Core\Services\Integracao\NovaXS\Dreams\DreamsService;
use TourFacil\Core\Services\Integracao\NovaXS\Exceed\ExceedService;
use TourFacil\Core\Services\Integracao\NovaXS\FantasticHouse\FantasticHouseService;
use TourFacil\Core\Services\Integracao\NovaXS\Matria\MatriaService;
use TourFacil\Core\Services\Integracao\NovaXS\MiniMundo\MiniMundoService;
use TourFacil\Core\Services\Integracao\NovaXS\NBAPark\NBAParkService;
use TourFacil\Core\Services\Integracao\NovaXS\Olivas\OlivasService;
use TourFacil\Core\Services\Integracao\NovaXS\Snowland\SnowlandService;
use TourFacil\Core\Services\Integracao\NovaXS\VilaDaMonica\VilaDaMonicaService;
use TourFacil\Core\Services\Integracao\PWI\PWIService;

/**
 * IntegracaoController
 */
class IntegracaoController extends Controller
{
    /**
     * Força a integração da reserva com o parque
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Foundation\Application|\Illuminate\Http\Response
     */
    public function integrarReserva(Request $request) {

        $reserva_id = $request->get('reserva_id');

        $reserva = ReservaPedido::find($reserva_id);

        if($reserva == null) {
            return response(['status'=> false, 'info' => 'Reserva não encontrada.'], 200);
        }

        try {

            switch($reserva->servico->integracao) {
                case IntegracaoEnum::SNOWLAND:
                    (new SnowlandService($reserva))->gerarVoucherSnowland();
                    break;

                case IntegracaoEnum::EXCEED_PARK:
                    (new ExceedService($reserva))->gerarVoucherExceed();
                    break;

                case IntegracaoEnum::OLIVAS:
                    (new OlivasService($reserva))->gerarVoucherOlivas();
                    break;

                case IntegracaoEnum::MINI_MUNDO:
                    (new MiniMundoService($reserva))->gerarVoucherMiniMundo();
                    break;

                case IntegracaoEnum::DREAMS:
                    (new DreamsService($reserva))->gerarVoucherDreams();
                    break;

                case IntegracaoEnum::ALPEN:
                    (new AlpenService($reserva))->gerarVoucherAlpen();
                    break;

                case IntegracaoEnum::FANTASTIC_HOUSE:
                    (new FantasticHouseService($reserva))->gerarVoucherFantasticHose();
                    break;

                case IntegracaoEnum::MATRIA:
                    (new MatriaService($reserva))->gerarVoucherMatria();
                    break;

                case IntegracaoEnum::VILA_DA_MONICA:
                    (new VilaDaMonicaService($reserva))->gerarVoucherVilaDaMonica();
                    break;

                case IntegracaoEnum::SKYGLASS:

                    if(!PWIService::integrarReserva($reserva)) return response(['status' => false, 'info' => "Houve um erro na integração.\n Detalhes enviados no e-mail."]);
                    break;

                case IntegracaoEnum::ACQUA_MOTION:
                    (new AcquaMotionService($reserva))->gerarVoucherAcquaMotion();
                    break;

                case IntegracaoEnum::NBA_PARK:
                    (new NBAParkService($reserva))->gerarVoucherNBAPark();
                    break;

                default:
                    return response(['status' => false, 'info' => 'Integração não encontrada na reserva.'], 200);
            }

            return response(['status' => true, 'info' => "A reserva #{$reserva->voucher} foi integrada com sucesso."], 200);

        } catch ( \Exception $e) {
            return response(['status' => false, 'info' => "Erro - {$e->getMessage()}"]);
        }
    }
}
