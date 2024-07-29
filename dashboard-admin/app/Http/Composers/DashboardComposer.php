<?php namespace App\Http\Composers;

use App\Services\UserService;
use Illuminate\View\View;
use TourFacil\Core\Services\CanalVendaService;

/**
 * Class DashboardComposer
 * @package App\Http\Composers
 */
class DashboardComposer
{
    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        // Caso o usuario logou via remember token
        if(canalSession()->hasCanal() == false && auth()->check()) {
            UserService::configureCanalUserDefault();
        }

        // Retira as views do breadcrumbs
        if($view->getName() != "breadcrumbs::bootstrap4") {

            // Menu lateral
            if(! $view->__isset('menu_sidebar')) {
                // Read cookie sidebar
                //$sidebar = Cookie::get('menu_sidebar', 'close');
                // Share menu sidebar
                $view->with('menu_sidebar', "close");
            }

            // Canais de vendas da navbar
            if(! $view->__isset('canais_composer')) {
                // Recupera os canais de venda
                $canais_navbar = CanalVendaService::getCanaisDeVendaAtivos();
                // Share canais de venda sidebar
                $view->with('canais_composer', $canais_navbar);
            }
        }
    }
}
