<?php namespace App\Http\View\Composers;

use App\Http\Controllers\Controller;
use App\Services\NavbarService;
use Illuminate\View\View;
use TourFacil\Core\Services\Cache\DestinoCacheService;

/**
 * Class NavbarViewComposer
 * @package App\Http\View\Composers
 */
class NavbarViewComposer
{
    /**
     * Paths onde será puxado o destino default do site
     *
     * @var array
     */
    protected $expects = [
        "cliente",
        "pesquisar"
    ];

    /**
     * Bind data to the view.
     *
     * @param View $view
     * @throws \Exception
     */
    public function compose(View $view)
    {
        // Caso nao exista as categorias da sidebar
        if(! $view->__isset('categorias_navbar')) {

            // Recupera o destino na URL
            $destino_slug = $this->getDestinoUrl($view);

            // Categorias do destino
            $categorias_navbar = NavbarService::categoriasNavbar($destino_slug);

            $view->with('categorias_navbar', $categorias_navbar);
        }
    }

    /**
     * Recupera o destino na URL
     * Caso a URl tenha exceção verifica se possui destino na sessao
     * Senão é retornado o destino default do canal
     *
     * @param $view
     * @return string|null
     * @throws \Exception
     */
    private function getDestinoUrl(View $view) {

        // Destino na primeira posição da URL
        $destino_slug = request()->segment(1);

        // Caso seja uma URL normal verifica se possui exeção
        if(in_array($destino_slug, $this->expects)) {

            // Recupera o destino na sessao
            $destino_sessao = session()->get(Controller::KEY_DESTINO);

            // Caso possua destino na sessao retorna o destino como default
            if(is_array($destino_sessao)) {
                return $destino_sessao['destino_slug'];
            }

            // Recupera o destino default do canal de venda
            $destino = DestinoCacheService::getDestinoDefaultCanal();

            // Salva o destino na sessao
            session()->put(Controller::KEY_DESTINO, [
                'destino' => $destino,
                'destino_slug' => $destino->slug,
                'url_destino' => route('ecommerce.destino.index', $destino->slug)
            ]);

            // Coloca a variavel destino nas views
            $view->with('destino', $destino);

            return $destino->slug;
        }

        return $destino_slug;
    }
}
