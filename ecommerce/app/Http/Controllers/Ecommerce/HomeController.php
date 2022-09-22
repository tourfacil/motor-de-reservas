<?php namespace App\Http\Controllers\Ecommerce;

use App\Enum\TourFacilEnum;
use App\Enum\DestinoBreveEnum;
use App\Enum\PostBlogEnum;
use App\Http\Controllers\Controller;
use Artisan;
use Illuminate\Http\Request;
use TourFacil\Core\Enum\ServicoEnum;
use TourFacil\Core\Models\Cliente;
use TourFacil\Core\Services\Cache\DefaultCacheService;
use TourFacil\Core\Services\Cache\DestinoCacheService;
use TourFacil\Core\Services\NewsletterService;
use TourFacil\Core\Services\Pagamento\Getnet\Service\RequestConnect;

/**
 * Class HomeController
 * @package App\Http\Controllers\Ecommerce
 */
class HomeController extends Controller
{
    /**
     * Pagina inicial do site
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     */
    public function index()
    {
        // Destinos em breve
        $breve_destinos = DestinoBreveEnum::BREVE_DESTINOS;

        // Banners dos destinos na home
        $banners_home = TourFacilEnum::BANNERS_HOME;

        // Posts fixo do blog
        $post = PostBlogEnum::POST;

        // Parceiros na home
        $parceiros = TourFacilEnum::LOGO_PARCEIROS;

        // Link de posts na midia
        $midias = TourFacilEnum::LOGO_MIDIAS;

        // Servicos em destaques na nome
        $servicos_destaques = TourFacilEnum::destaquesHomeTourFacil();

        // Destino defautl do site
        $destino_default = DestinoCacheService::getDestinoDefaultCanal();

        // Detalhes do destino destaque ativo
        $destino_destaque = $this->collectionDestinoDestaque(
            DestinoCacheService::detalhesDestinoHome($destino_default->slug)
        );

        return view('index', compact(
            'breve_destinos',
            'post', 'destino_destaque',
            'banners_home', 'parceiros',
            'midias', 'servicos_destaques'
        ));
    }

    /**
     * Detalhes do destino utilizado na home AJAX
     *
     * @param null $destino_slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     * @throws \Exception
     */
    public function view($destino_slug = null)
    {
        if(is_null($destino_slug)) return $this->redirect('ecommerce.index');

        // Detalhes do destino destaque ativo
        $destino_destaque = $this->collectionDestinoDestaque(
            DestinoCacheService::detalhesDestinoHome($destino_slug)
        );

        return view('partials.view-destino-home', compact('destino_destaque'));
    }

    /**
     * Monta um array para os detalhes do destino com os servicos na home
     *
     * @param $destino_destaque
     * @return array
     */
    public function collectionDestinoDestaque($destino_destaque)
    {
        $return = [
            'slug' => $destino_destaque->slug,
            'nome' => $destino_destaque->nome,
            'destino' => breakWord($destino_destaque->nome),
            'foto' => $destino_destaque->foto_destino,
            'url' => route('ecommerce.destino.index', $destino_destaque->slug),
            'descricao' => markdown_cache($destino_destaque->descricao_completa, "destaqueh_{$destino_destaque->id}"),
            'servicos' => [],
            'categorias' => []
        ];

        $servicos = $destino_destaque->homeDestinoDestaque->first()->servicosAtivos;

        foreach ($servicos as $servico) {
            $tags = [];
            $categoria = $servico->categoria;
            // Limita as tags para 2
            $servico->tags->splice(ServicoEnum::LIMITE_TAGS);
            foreach ($servico->tags as $tag) {
                $tags[] = ['icone' => $tag->icone, 'descricao' => $tag->descricao];
            }

            $return['servicos'][] = [
                'nome' => $servico->nome,
                'valor_venda' => formataValor($servico->valor_venda),
                'foto' => $servico->fotoPrincipal->foto_large,
                'destino' => $destino_destaque->nome,
                'tags' => $tags,
                'url' => route('ecommerce.servico.view', [$destino_destaque->slug, $categoria->slug, $servico->slug]),
            ];
            $return['categorias'][$categoria->id] = [
                'nome' => $categoria->nome,
                'url' => route('ecommerce.categoria.index', [$destino_destaque->slug, $categoria->slug])
            ];
        }

        // Retira o id das categorias
        $return['categorias'] = array_values($return['categorias']);

        return $return;
    }

    /**
     * Verifica se o email do cliente ja esta sendo usando no canal atual
     *
     * @param Request $request
     * @return array
     * @throws \Exception
     */
    public function verificarEmail(Request $request)
    {
        // Email do cliente passado via POST
        $email = $request->get('email');

        // ID do canal de venda atual env
        $canal_venda_id = DefaultCacheService::getCanalVenda();

        // Verifica se o cliente ja esta cadastrado no canal de venda atual
        $has_cliente = Cliente::where('email', 'like', $email)
            ->where('canal_venda_id', $canal_venda_id)->limit(1)->get();

        return [
            'email_exists' => ($has_cliente->count() > 0)
        ];
    }

    /**
     * POST para cadastro do email na newsletter
     *
     * @param Request $request
     * @return array
     * @throws \Illuminate\Validation\ValidationException
     */
    public function newsletter(Request $request)
    {
        // Valida a requisao
        $this->validate($request, ['newsletter' => 'email|required']);

        // ID do canal de venda atual env
        $canal_venda_id = DefaultCacheService::getCanalVenda();

        // Cadastra o email
        NewsletterService::store($request->get('newsletter'), $canal_venda_id);

        return ['store' => true];
    }

    /**
     * Limpa o cache da aplicação
     *
     * @return array
     */
    public function cacheClear()
    {
        Artisan::call("cache:clear");

        return ["cache" => true];
    }

    public function cancelGetnet()
    {
        $request = new RequestConnect();

        $response = $request->connect_api("/v1/payments/cancel/request", "POST", [
            "payment_id" => "",
            "cancel_amount" => 10
        ]);

        dd($response);
    }
}
