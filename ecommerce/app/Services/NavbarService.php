<?php namespace App\Services;

use Exception;
use Illuminate\Support\Arr;
use TourFacil\Core\Models\Categoria;
use TourFacil\Core\Services\Cache\DefaultCacheService;
use TourFacil\Core\Services\Cache\ServicoCacheService;

class NavbarService extends DefaultCacheService
{
    /**
     * Categorias na navbar
     *
     * @param $destino_slug
     * @return mixed
     * @throws Exception
     */
    public static function categoriasNavbar($destino_slug)
    {
        // Recupera o canal id no env
        $canal_id = self::getCanalVenda();

        return self::run(true, __FUNCTION__ . $destino_slug, function () use ($destino_slug, $canal_id) {
            // Todas as categorias do site com as secoes
            return Categoria::whereHas('destino', function ($query) use ($destino_slug, $canal_id) {
                return $query->where(['canal_venda_id' => $canal_id, 'slug' => $destino_slug]);
            })->whereHas('servicosAtivos')->orderBy('posicao_menu')->get(['nome', 'slug', 'id']);
        });
    }
}
