<?php namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

/**
 * Class ViewServiceProvider
 * @package App\Providers
 */
class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function boot()
    {
        // Using class based composers...
        if($this->app->runningInConsole() == false) {
            // Lista dos destinos
            View::composer('*', 'App\Http\View\Composers\DestinosViewComposer');
            // Ofertas no site
            //View::composer('*', 'App\Http\View\Composers\OfertaViewComposer');
            // Categorias na navbar
            View::composer([
                'template.navbar',
                'paginas.cliente.pedidos',
                'paginas.cliente.detalhe-pedido',
            ], 'App\Http\View\Composers\NavbarViewComposer');
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
