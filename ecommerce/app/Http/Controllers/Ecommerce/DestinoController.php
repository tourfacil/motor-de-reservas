<?php namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use TourFacil\Core\Services\Cache\BannerCacheService;
use TourFacil\Core\Services\Cache\CategoriaCacheService;
use TourFacil\Core\Services\Cache\DestinoCacheService;

/**
 * Class DestinoController
 * @package App\Http\Controllers\Ecommerce
 */
class DestinoController extends Controller
{
    /**
     * Home destino
     *
     * @param $destino_slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     * @throws \Exception
     */
    public function index($destino_slug)
    {
        // Detalhes do destino
        $destino = DestinoCacheService::destinoHomeSlug($destino_slug);

        // Redirecionamento caso nao encontre o destino
        if(is_null($destino)) return $this->redirect();

        // Salva o destino na sessao
        $this->setDestinoSession($destino);

        // Categorias do destino
        $categorias = CategoriaCacheService::categoriasDestino($destino->id);

        // Banner home
        $banner = BannerCacheService::bannersDestino($destino->id, true);

        // Info que é a index do destino
        $index_destino = true;

        // Posicao dos servicos
        $position = 1;

        return view('paginas.destino', compact(
            'destino',
            'categorias',
            'index_destino',
            'position',
            'banner'
        ));
    }
}
