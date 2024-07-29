<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use TourFacil\Core\Services\Cache\CategoriaCacheService;
use TourFacil\Core\Services\Cache\ServicoCacheService;

/**
 * Class CrmController
 * @package App\Http\Controllers
 */
class CrmController extends Controller
{
    /**
     * Limpa o cache da aplicação
     *
     * @return array
     */
    public function cleanCache()
    {
        // Limpa o cache da aplicacao
        $clear = \Artisan::call('cache:clear');

        return ['cache' => $clear];
    }
}
