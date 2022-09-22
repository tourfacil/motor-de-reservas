<?php namespace App\Http\Controllers\Ecommerce;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use TourFacil\Core\Models\Servico;
use TourFacil\Core\Models\AgendaDataServico;
use TourFacil\Core\Models\VariacaoServico;
use TourFacil\Core\Models\VendaInternaLink;
use TourFacil\Core\Models\Afiliado;
use App\Services\ConferenciaReserva;

/**
 * Class CarrinhoController
 * @package App\Http\Controllers\Ecommerce
 */
class CarrinhoController extends Controller
{
    /**
     * Carrinho de compras
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     */
    public function index()
    {
        // Carrinho de compras
        $carrinho = carrinho()->all();

        //dd($carrinho);
        
        // Verifica se os produtos que estão no carrinho ainda tem disponibilidade e se ainda estão com o mesmo preço
        // O método verifica se a quantidade de vagas do banco é suficiente para suprir as pessoas do carrinho
        // O método retorna um array com todos os serviços e junto se ainda estão atualizados
        //$resposta = ConferenciaReserva::confereValorEVagasDaSessao();

        // Destino salvo na sessao
        $destino_atual = $this->getDestinoSession();

        // URL para a logo
        $url_logo = $destino_atual['url_destino'] ?? route('ecommerce.index');

        // Valor total do carrinho
        $total_carrinho = $carrinho->sum('valor_total');

        // Perguntas e respostas
        $perguntas = $this->faq();

        // Verifica se existe a query string de expirado
        $expirado = request()->has('expirado');

        $dados = [
            'carrinho' => $carrinho,
            'url_logo' => $url_logo,
            'total_carrinho' => $total_carrinho,
            'perguntas' => $perguntas,
            'expirado' => $expirado,
        ];

        return view('paginas.checkout.carrinho', $dados);
    }

    /**
     * @return array
     */
    public function faq()
    {
        return [
            [
                'questao' => 'O pagamento é seguro?',
                'resposta' => 'Sim. Toda a comunicação com o nosso servidor é criptografada com certifcado SSL, e não armazenamos nenhuma informação sobre seu cartão de crédito.',
            ],
            [
                'questao' => 'Posso alterar a minha reserva posteriormente?',
                'resposta' => 'Sim. Para alterar as informações dos viajantes (nome, documentos, hotel entre outros) basta entrar MINHAS RESERVAS. Para alteração da data de utilização da reserva, você deverá solicitar com até 7 (SETE) dias de antecedência, mediante a autorização do fornecedor do serviço.',
            ],
            [
                'questao' => 'Quais são as formas de pagamento disponíveis?',
                'resposta' => 'Você pode pagar suas compras com cartão de crédito à vista ou parcelado em até 10x sem juros com as seguintes bandeiras: Visa, MasterCard, Hipercard, Elo e Amex. Para mais formas de pagamentos, entre em contato conosco via e-mail ou Whatsapp.',
            ]
        ];
    }

    /**
     * Adicionar o servico no carrinho
     *
     * @param Request $request
     * @return array
     */
    public function add(Request $request)
    {
        $result = carrinho()->add($request->all());

        return [
            'adicionar' => $result,
            'route' => route('ecommerce.carrinho.index'),
        ];
    }

    /**
     * Remove o servico do carrinho de compras
     *
     * @param $servico_uuid
     * @return array
     */
    public function remove($servico_uuid)
    {
        return ['remove' => carrinho()->remove($servico_uuid)];
    }

    /**
     * POST para transformar o form em json
     *
     * @param Request $request
     * @return array
     */
    public function formParse(Request $request)
    {
        return $request->all();
    }

    /**
     * Limpa o carrinho de compras
     *
     * @return array
     */
    public function zerarCarrinho()
    {
        carrinho()->destroy();

        return ['carrinho' => true];
    }

    public function linkVenda(Request $request, $uuid) {

        $venda_interna = VendaInternaLink::where('uuid', $uuid)->get()->first();

        if($venda_interna->afiliado_id != null) {
            $afiliado = Afiliado::find($venda_interna->afiliado_id);

            if($afiliado != null) {
                session(['afiliado' => $afiliado]);
            }
        } 

        if($venda_interna == null) {
            return redirect()->route('ecommerce.carrinho.index');
        }

        $carrinho = json_decode($venda_interna->carrinho, true);

        session(['carrinho' => $carrinho]);

        return redirect()->route('ecommerce.carrinho.index');
    }
}
