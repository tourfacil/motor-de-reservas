<?php namespace App\Http\View\Composers;

use App\Enum\OfertaEnum;
use Illuminate\View\View;

/**
 * Class OfertaViewComposer
 * @package App\Http\View\Composers
 */
class OfertaViewComposer
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
        if(! $view->__isset('is_black')) {

            // Detalhes da oferta
            $oferta = OfertaEnum::getOferta();

            $view->with($oferta);
        }
    }
}
