<?php

namespace App\Enum;

use TourFacil\Core\Services\Cache\DefaultCacheService;
use TourFacil\Core\Services\Cache\ServicoCacheService;

/**
 * Class TourFacilEnum
 * @package App\Enum
 */
abstract class TourFacilEnum extends DefaultCacheService
{
    // Banners na home do site
    const BANNERS_HOME = [
        ['image' => '/images/banners/bondinhos-aereos-canela.jpeg'],
        ['image' => '/images/banners/igreja-gramado.jpeg'],
    ];

    /**
     * Logo dos parceiros na home
     */
    const LOGO_PARCEIROS = [
        ['logo' => '/images/parceiros/logo_brockerturismo.svg', 'nome' => 'Brocker Turismo'],
        ['logo' => '/images/parceiros/logo_snowland.png', 'nome' => 'Parque Snowland'],
        ['logo' => '/images/parceiros/logo_betocarrero.png', 'nome' => 'Beto Carrero'],
        ['logo' => '/images/parceiros/logo_cataratas.jpg', 'nome' => 'Cataratas'],
        ['logo' => '/images/parceiros/logo_gramadozoo.png', 'nome' => 'Gramado Zoo'],
        ['logo' => '/images/parceiros/logo_alpenpark.png', 'nome' => 'Alpen Park'],
        ['logo' => '/images/parceiros/logo_florybal.png', 'nome' => 'Parque Florybal'],
    ];

    /**
     * Logo de publicacoes na midia
     */
    const LOGO_MIDIAS = [
        ['logo' => '/images/parceiros/logo_panrotas.png', 'nome' => 'Panrotas', 'url' => "#"],
        ['logo' => '/images/parceiros/logo_exame.svg', 'nome' => 'Exame', 'url' => "#"],
        ['logo' => '/images/parceiros/logo_folha.svg', 'nome' => 'Folha de São Paulo', 'url' => "#"],
    ];

    // ID dos servicos em destaque na home (1 slider)
    const SERVICOS_DESTAQUES_HOME = [107, 73, 82, 68, 5, 71];

    /**
     * Detalhes dos servicos em destaques na home do site
     *
     * @param bool $cache
     * @return mixed
     */
    public static function destaquesHomeTourFacil($cache = true)
    {
        return self::run($cache, __FUNCTION__, function () {
            $return = collect();
            // Recupera as informacoes do servicos na constante
            $servicos = ServicoCacheService::detalheServicosId(self::SERVICOS_DESTAQUES_HOME);

            // Ordena os servicos de acordo com o array
            foreach (self::SERVICOS_DESTAQUES_HOME as $id_servico) {
                foreach ($servicos as $servico) {
                    if ($servico->id == $id_servico) {
                        $return->push($servico);
                        break;
                    }
                }
            }

            return $return;
        });
    }
}
