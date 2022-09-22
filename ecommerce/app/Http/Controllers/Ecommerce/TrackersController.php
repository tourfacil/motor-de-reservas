<?php namespace App\Http\Controllers\Ecommerce;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use TourFacil\Core\Services\Cache\ServicoCacheService;
use Response;

/**
 * Class TrackersController
 * @package App\Http\Controllers\Ecommerce
 */
class TrackersController extends Controller
{
    /**
     * CSV para o catalogo de produtos do Facebook
     *
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function facebookCsv()
    {
        $servicos = ServicoCacheService::servicosFeedFacebook();

        return Response::view('feed.csv-facebook', compact('servicos'))
            ->header('Content-Type', 'text/plain; charset=UTF-8')
            ->header('Content-Encoding', 'UTF-8');
    }

    /**
     * Google Merchant Center
     *
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function googleCsv()
    {
        $servicos = ServicoCacheService::servicosFeedFacebook();

        return Response::view('feed.csv-google', compact('servicos'))
            ->header('Content-Type', 'text/plain; charset=UTF-8')
            ->header('Content-Encoding', 'UTF-8');
    }

    /**
     * Google Adwords Remarketing
     *
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function googleAdwords()
    {
        $servicos = ServicoCacheService::servicosFeedFacebook();

        return Response::view('feed.csv-adwords', compact('servicos'))
            ->header('Content-Type', 'text/plain; charset=UTF-8')
            ->header('Content-Encoding', 'UTF-8');
    }
}
