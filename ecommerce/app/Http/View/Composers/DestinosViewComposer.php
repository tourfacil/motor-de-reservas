<?php namespace App\Http\View\Composers;

use Illuminate\View\View;
use TourFacil\Core\Services\Cache\DestinoCacheService;

/**
 * Class DestinosViewComposer
 * @package App\Http\View\Composers
 */
class DestinosViewComposer
{
    /**
     * Bind data to the view.
     *
     * @param View $view
     * @throws \Exception
     */
    public function compose(View $view)
    {
        // Caso nao exista as categorias da sidebar
        if(! $view->__isset('destinos')) {

            // Recupera os destinos do canal de venda
            $destinos = DestinoCacheService::destinosAtivoSite();

            // Passa para todas as view a variavel categorias
            $view->with('destinos', $destinos);
        }
    }
}
