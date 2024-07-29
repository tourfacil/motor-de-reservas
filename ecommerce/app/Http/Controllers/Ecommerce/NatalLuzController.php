<?php

namespace App\Http\Controllers\Ecommerce;

use App\Enum\NatalLuzEnum;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use TourFacil\Core\Enum\CategoriasEnum;
use TourFacil\Core\Enum\ServicoEnum;
use TourFacil\Core\Models\Categoria;
use TourFacil\Core\Models\Destino;
use TourFacil\Core\Models\Servico;
use TourFacil\Core\Services\AvaliacaoService;
use TourFacil\Core\Services\Cache\CategoriaCacheService;
use TourFacil\Core\Services\Cache\DestinoCacheService;
use TourFacil\Core\Services\Cache\ServicoCacheService;

class NatalLuzController extends Controller
{
    public function show(Request $request)
    {
        if(NatalLuzEnum::PRODUTO_ATIVO == false) {
            return redirect()->route('ecommerce.index');
        }

        $servico = Servico::find(NatalLuzEnum::SERVICO_BASE);
        $destino_slug = $servico->destino->slug;
        $categoria_slug = $servico->categorias->first()->slug;
        $servico_slug = $servico->slug;

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

        // Evitar  BUGS quando o serviço for desativado ou editado e siga sendo acessado de alguma forma
        if($servico != null) {

            $avaliacoes = AvaliacaoService::getAvaliacoesAprovadas($servico);

            $nota_media = AvaliacaoService::getNotaMedia($servico);

            $total_avaliacoes = AvaliacaoService::getQuantidadeTotalAvaliacoes($servico);
        }

        // Redirecionamento caso nao encontre o servico
        if (is_null($servico)) return $this->redirect(route('ecommerce.categoria.index', [$destino_slug, $categoria_slug]));

        // Verifica as categorias do servico
        $verifica_categoria = $this->verificaCategorias($servico, $categoria);

        // Categoria default do serviço
        $categoria_padrao = $verifica_categoria['padrao'];

        // Caso o servico nao tenha a categoria da URL redireciona para a url com a categoria default
        if (!$verifica_categoria['has_category']) return $this->redirect(route('ecommerce.servico.view', [$destino_slug, $categoria_padrao->slug, $servico->slug]));

        // Servicos relacionados
//        $relacionados = ServicoCacheService::servicosRelacionados($servico->id, $categoria->id);
        $relacionados = Servico::find(NatalLuzEnum::SERVICOS);

        // Status do servico ativo
        $ativo = ($servico->status == ServicoEnum::ATIVO || $servico->status == ServicoEnum::INVISIVEL);

        // Foto destaque do servico
        $foto_destaque = $servico->fotos->first();

        // Dados do canal de venda
        $canal_venda = $this->getCanalVenda();

        // Marca a categoria atual na nav
        $current_categoria = $categoria->id;

        $perguntas = $this->faq();

        return view('paginas.servico-natal-luz', compact(
            'destino',
            'categoria',
            'servico',
            'relacionados',
            'ativo',
            'foto_destaque',
            'categoria_padrao',
            'canal_venda',
            'current_categoria',
            'perguntas',
            'avaliacoes',
            'nota_media',
            'total_avaliacoes'
        ));



    }

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
                'resposta' => 'Você pode pagar suas compras com cartão de crédito à vista ou parcelado em até 12x com as seguintes bandeiras: Visa, MasterCard, Hipercard, Elo e Amex. Para mais formas de pagamentos, entre em contato conosco via Whatsapp.',
            ]
        ];
    }
}
