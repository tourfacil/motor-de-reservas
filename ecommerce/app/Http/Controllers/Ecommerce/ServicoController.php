<?php

namespace App\Http\Controllers\Ecommerce;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use TourFacil\Core\Enum\CategoriasEnum;
use TourFacil\Core\Enum\ServicoEnum;
use TourFacil\Core\Models\Servico;
use TourFacil\Core\Services\AgendaService;
use TourFacil\Core\Services\Cache\CategoriaCacheService;
use TourFacil\Core\Services\Cache\DestinoCacheService;
use TourFacil\Core\Services\Cache\ServicoCacheService;

/**
 * Class ServicoController
 * @package App\Http\Controllers\Ecommerce
 */
class ServicoController extends Controller
{
    /**
     * View do servico
     *
     * @param null $destino_slug
     * @param null $categoria_slug
     * @param null $servico_slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     * @throws \Exception
     */
    public function view($destino_slug = null, $categoria_slug = null, $servico_slug = null)
    {
        // Detalhes do destino
        $destino = DestinoCacheService::destinoSlug($destino_slug);

        // Redirecionamento caso nao encontre o destino
        if (is_null($destino)) return $this->redirect();

        // Salva o destino na sessao
        $this->setDestinoSession($destino);

        // Recupera as informacoes da categoria
        $categoria = CategoriaCacheService::categoriaSlug($destino, $categoria_slug);

        // Redirecionamento caso nao encontre a categoria
        if (is_null($categoria)) return $this->redirect(route('ecommerce.destino.index', [$destino_slug]));

        // Detalhes do servico
        $servico = ServicoCacheService::servicoSlug($servico_slug);

        // Redirecionamento caso nao encontre o servico
        if (is_null($servico)) return $this->redirect(route('ecommerce.categoria.index', [$destino_slug, $categoria_slug]));

        // Verifica as categorias do servico
        $verifica_categoria = $this->verificaCategorias($servico, $categoria);

        // Categoria default do serviço
        $categoria_padrao = $verifica_categoria['padrao'];

        // Caso o servico nao tenha a categoria da URL redireciona para a url com a categoria default
        if (!$verifica_categoria['has_category']) return $this->redirect(route('ecommerce.servico.view', [$destino_slug, $categoria_padrao->slug, $servico->slug]));

        // Servicos relacionados
        $relacionados = ServicoCacheService::servicosRelacionados($servico->id, $categoria->id);

        // Status do servico ativo
        $ativo = ($servico->status == ServicoEnum::ATIVO || $servico->status == ServicoEnum::INVISIVEL);

        // Foto destaque do servico
        $foto_destaque = $servico->fotos->first();

        // Dados do canal de venda
        $canal_venda = $this->getCanalVenda();

        // Marca a categoria atual na nav
        $current_categoria = $categoria->id;

        $perguntas = $this->faq();

        //Redirecionamentos com status code 301
        if (
            $servico_slug == 'jantar-no-hard-rock-cafe-com-show-do-elvis-presley-sem-transporte' ||
            $servico_slug == 'jantar-noite-suica-sem-transporte' ||
            $servico_slug == 'ingresso-para-a-fabulosa-fabrica-de-cervejas' ||
            $servico_slug == 'ingresso-show-korvatunturi-setor-azul' ||
            $servico_slug == 'ingresso-show-korvatunturi-setor-vip' ||
            $servico_slug == 'ingresso-show-korvatunturi-setor-premium' ||
            $servico_slug == 'salao-super-carros-em-gramado-com-simulador-f1' ||
            $servico_slug == 'passeio-de-helicoptero-cascata-do-caracol' ||
            $servico_slug == 'tour-de-patinete-eletrico-em-gramado-tarde-1600' ||
            $servico_slug == 'tour-olivas-com-cafe-da-colonia' ||
            $servico_slug == 'tour-de-patinete-eletrico-em-gramado-manha-0900'
        ) {
            return redirect($destino_slug . '/' . $categoria_slug, 301);
        }

        if ($servico_slug == 'noite-gaucha-jantar-transporte') {
            return redirect($destino_slug . '/' . $categoria_slug . '/noite-gaucha-com-transporte', 301);
        }

        if ($servico_slug == 'jantar-noite-gaucha-sem-transporte') {
            return redirect($destino_slug . '/' . $categoria_slug . '/noite-gaucha-com-transporte', 301);
        }

        if ($servico_slug == 'jantar-noite-italiana-sem-transporte') {
            return redirect($destino_slug . '/' . $categoria_slug . '/noite-italiana-com-transporte', 301);
        }

        if ($servico_slug == 'ingresso-parque-terra-magica-florybal') {
            return redirect($destino_slug . '/' . $categoria_slug . '/ingresso-terra-magica-florybal', 301);
        }

        if ($servico_slug == 'passaporte-grupo-dreams') {
            return redirect($destino_slug . '/' . $categoria_slug . '/passaporte-7-atracoes-grupo-dreams', 301);
        }

        if ($servico_slug == 'ticket-bustour-um-dia-de-uso') {
            return redirect($destino_slug . '/' . $categoria_slug . '/ticket-bustour-1-dia-de-uso', 301);
        }

        if ($servico_slug == 'bus-bier-tour-sem-degustacao') {
            return redirect($destino_slug . '/' . $categoria_slug . '/bus-bier-tour-com-degustacao', 301);
        }

        if ($servico_slug == 'tour-canion-itaimbezinho-com-piquenique') {
            return redirect($destino_slug . '/' . $categoria_slug . '/tour-itaimbezinho-com-piquenique', 301);
        }

        if ($servico_slug == 'tour-linha-bella-com-almoco') {
            return redirect($destino_slug . '/' . $categoria_slug . '/tour-linha-bella', 301);
        }

        if ($servico_slug == 'tour-origens-alemas-nova-petropolis') {
            return redirect($destino_slug . '/' . $categoria_slug . '/tour-origens-germanicas-em-nova-petropolis', 301);
        }

        if ($servico_slug == 'tour-origens-alemas-com-almoco') {
            return redirect($destino_slug . '/' . $categoria_slug . '/tour-origens-germanicas-em-nova-petropolis', 301);
        }

        if ($servico_slug == 'tour-trem-e-vinho-com-ingresso-epopeia-italiana-e-trem-maria-fumaca') {
            return redirect($destino_slug . '/' . $categoria_slug . '/tour-uva-e-vinho-com-trem-maria-fumaca-e-almoco', 301);
        }

        if ($servico_slug == 'tour-trem-e-vinho-com-almoco') {
            return redirect($destino_slug . '/' . $categoria_slug . '/tour-uva-e-vinho-com-trem-maria-fumaca-e-almoco', 301);
        }

        if ($servico_slug == 'tour-gran-reserva-vale-dos-vinhedos') {
            return redirect($destino_slug . '/' . $categoria_slug . '/tour-vale-dos-vinhedos', 301);
        }

        return view('paginas.servico', compact(
            'destino',
            'categoria',
            'servico',
            'relacionados',
            'ativo',
            'foto_destaque',
            'categoria_padrao',
            'canal_venda',
            'current_categoria',
            'perguntas'
        ));
    }

    /**
     * Verifica se a categoria da URL esta no servico
     * E retorna a categoria default do serviço
     *
     * @param $servico
     * @param $categoria
     * @return array
     */
    private function verificaCategorias($servico, $categoria)
    {
        $return = ['padrao' => null, 'has_category' => false];

        foreach ($servico->categorias as $categoria_servico) {
            // Categoria padrao do servico
            if ($categoria_servico->padrao == CategoriasEnum::CATEGORIA_PADRAO) {
                $return['padrao'] = $categoria_servico;
            }
            // Caso tenha a categoria passada na URL
            if ($categoria_servico->id == $categoria->id) {
                $return['has_category'] = true;
            }
        }

        return $return;
    }

    /**
     * Detalhes do servico em JSON
     *
     * @param null $uuid
     * @return array|\Illuminate\Http\RedirectResponse
     */
    public function viewJson($uuid = null)
    {
        if (request()->ajax()) {

            // Categoria atual do servico
            $category_slug = request('category');

            // Recupera os dados do servico
            $servico = Servico::with([
                'fotoPrincipal', 'destino',
                'fornecedor' => function ($f) {
                    return $f->select(['id', 'uuid', 'nome_fantasia']);
                },
                'categorias' => function ($q) use ($category_slug) {
                    return $q->where('slug', $category_slug);
                }
            ])->where('uuid', $uuid)->first([
                'id', 'uuid', 'slug', 'fornecedor_id', 'nome', 'integracao', 'valor_venda',
                'horario', 'localizacao', 'cidade', 'destino_id'
            ]);

            return [
                'id' => $servico->id,
                'uuid' => $servico->uuid,
                'nome' => $servico->nome,
                'integracao' => $servico->integracao,
                'valor_venda' => $servico->valor_venda,
                'localizacao' => $servico->localizacao,
                'horario' => $servico->horario,
                'cidade' => $servico->cidade,
                'foto_servico' => $servico->fotoPrincipal->foto_large,
                'destino' => ['id' => $servico->destino->id, 'nome' => $servico->destino->nome],
                'categoria' => ['id' => $servico->categoria->id, 'nome' => $servico->categoria->nome],
                'fornecedor' => ['uuid' => $servico->fornecedor->uuid, 'nome' => $servico->fornecedor->nome_fantasia],
                'urls' => [
                    'calendario' => route('ecommerce.servico.calendario', $servico->uuid),
                    'servico' => route('ecommerce.servico.view', [$servico->destino->slug, $servico->categoria->slug, $servico->slug]),
                    'carrinho' => route('ecommerce.carrinho.add')
                ]
            ];
        }

        return redirect()->route('ecommerce.index');
    }

    /**
     * Datas para venda do servico
     *
     * @param null $uuid
     * @return array|\Illuminate\Http\RedirectResponse
     */
    public function calendario($uuid = null)
    {
        if (request()->ajax()) {

            // Recupera os dados do servico
            $agenda = AgendaService::disponibilidadeSite($uuid);

            // Recupera os dados selecionados no carrinho
            if (request()->get('carrinho') == "true") {
                $agenda['carrinho'] = carrinho()->get($uuid) ?? [];
                $agenda['carrinho']['urls']['carrinho'] = route('ecommerce.carrinho.add');
            }

            return $agenda;
        }

        return redirect()->route('ecommerce.index');
    }

    /**
     * @return array
     */
    public function faq()
    {
        return [
            [
                'questao' => 'A reserva da atividade é instantânea?',
                'resposta' => 'Sim! Após a confirmação do pagamento sua reserva já estará agendada. Agora é só apresentar o voucher na data da atividade e curtir sua viagem. ',
            ],
            [
                'questao' => 'Posso alterar os dados da reserva posteriormente?',
                'resposta' => 'Sim. Para alterar as informações dos viajantes ou a data de utilização da reserva, você deverá solicitar com até 7 (SETE) dias de antecedência, mediante a autorização do fornecedor do serviço.',
            ],
            [
                'questao' => 'Quais são as formas de pagamento disponíveis?',
                'resposta' => 'Você pode pagar suas compras com cartão de crédito à vista ou parcelado em até 12x (até 3x sem juros ou de 4 a 12 com 2% de juros ao mês) com as seguintes bandeiras: Visa, MasterCard, Hipercard, Elo e Amex. Para mais formas de pagamentos, entre em contato conosco via Whatsapp.',
            ]
        ];
    }
}
