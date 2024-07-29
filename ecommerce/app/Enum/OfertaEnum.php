<?php namespace App\Enum;

use Illuminate\Support\Carbon;

/**
 * Class OfertaEnum
 * @package App\Enum
 */
abstract class OfertaEnum
{
    /** @var string Final da oferta */
    const endDate = "2020-01-01 23:59:59";

    /**
     * Detalhes da oferta
     *
     * @return array
     */
    public static function getOferta()
    {
        // URL do banner
        $url_banner = route('ecommerce.categoria.index', ['gramado', 'mega-promo']);

        // Data final da oferta
        $endDateOferta = Carbon::parse(self::endDate);

        $servicos = [
//            0 => [
//                'valor_normal' => 'R$ 145,00',
//                'valor_promocao' => 'R$ 135,00',
//                'price' => 135,
//                'porcentagem' => '10',
//                'class' => 'bf-card',
//                'oferta' => true,
//            ],
        ];

        return [
            'url_banner' => $url_banner,
            'is_black' => $endDateOferta->isFuture(),
            'servicos_oferta' => $servicos,
            'destinos_oferta' => [2]
        ];
    }
}
