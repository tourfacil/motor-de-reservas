<?php

namespace App\Http\Controllers\Ecommerce;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Enum\TourFacilEnum;
use TourFacil\Core\Enum\ServicoEnum;
use TourFacil\Core\Services\Cache\DestinoCacheService;
use App\Enum\PostBlogEnum;

class LandingPageController extends Controller
{
    public function index()
    {
        // Parceiros na home
        $parceiros = TourFacilEnum::LOGO_PARCEIROS;

        // Posts fixo do blog
        $post = PostBlogEnum::POST;

        // Servicos em destaques na nome
        $servicos_destaques = TourFacilEnum::destaquesHomeTourFacil();

        // Destino defautl do site
        $destino_default = DestinoCacheService::getDestinoDefaultCanal();

        // Detalhes do destino destaque ativo
        $destino_destaque = $this->collectionDestinoDestaque(
            DestinoCacheService::detalhesDestinoHome($destino_default->slug)
        );

        return view('paginas.marketing.landingPage', compact(
            'parceiros',
            'servicos_destaques',
            'destino_destaque',
            'post',
        ));
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
}
