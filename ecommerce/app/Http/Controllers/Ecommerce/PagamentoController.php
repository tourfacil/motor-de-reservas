<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Requests\Ecommerce\PagamentoRequest;
use App\Jobs\NovaVendaJob;
use App\Notifications\NovoClienteNotification;
use Arr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Jenssegers\Agent\Agent;
use Notification;
use TourFacil\Core\Enum\MetodoPagamentoEnum;
use TourFacil\Core\Enum\OrigemEnum;
use TourFacil\Core\Models\CanalVenda;
use TourFacil\Core\Services\Cache\DefaultCacheService;
use TourFacil\Core\Services\ClienteService;
use TourFacil\Core\Services\CupomDescontoService;
use TourFacil\Core\Services\FinalizacaoService;
use TourFacil\Core\Services\Pagamento\CartaoService;
use TourFacil\Core\Services\Pagamento\DescontoPIXService;
use TourFacil\Core\Services\PedidoService;
use Storage;
use TourFacil\Core\Enum\StatusPedidoEnum;
use TourFacil\Core\Enum\StatusPixEnum;
use TourFacil\Core\Models\EnderecoCliente;
use TourFacil\Core\Models\Pedido;
use TourFacil\Core\Services\Pagamento\PixService;
use Carbon\Carbon;

/**
 * Class PagamentoController
 * @package App\Http\Controllers\Ecommerce
 */
class PagamentoController extends Controller
{
    /**
     * Historico do passos para gerar o pedido
     *
     * @var string
     */
    protected $history = "";

    /**
     * Número do pedido gerado
     *
     * @var string
     */
    protected $numero_pedido = "";

    /**
     * Pagina de pagamento
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     */
    public function index()
    {

        // Destino salvo na sessao
        $destino_atual = $this->getDestinoSession();

        // URL para a logo na navbar
        $url_logo = $destino_atual['url_destino'] ?? route('ecommerce.index');

        // Carrinho de compras
        $carrinho = carrinho()->all();

        // Caso o carrinho esteja vazio
        if (!count($carrinho)) return redirect($url_logo);

        // Valor total do carrinho
        $total_carrinho = $carrinho->sum('valor_total');

        // Salva o valor original, para caso tenha desconto de cupom
        $total_original = $total_carrinho;

        // Caso tenha cupom ativo, altera o valor do carrinho
        if(session()->exists('cupom_desconto')) {
            $total_carrinho = CupomDescontoService::getValorTotalCarrinhoComCupom($total_carrinho);
        }

        $total_carrinho_pix = $total_carrinho;
        if(DescontoPIXService::isDescontoPIXAplicavel()) {
            $total_carrinho_pix = DescontoPIXService::calcularValorPixDesconto($total_carrinho);
        }

        // Recupera os dados do canal de venda
        $canal_venda = CanalVenda::find(DefaultCacheService::getCanalVenda());

        // Calcula o parcelamento
        $parcelamento = $this->parcelamento($total_carrinho, $canal_venda);

        // Verifica de onde o cliente está acessando
        $is_mobile = (new Agent())->isMobile();

        // Parcelamento possivel
        $ultima_parcela = Arr::last($parcelamento['parcelamento']);

        // Pagamento com Cartão de Credito
        $e_pagamento_cartao = MetodoPagamentoEnum::CARTAO_CREDITO;

        // Dados do cliente logado
        $cliente = auth()->user();

        // Remove as informações da sessao
        session()->forget([
            "pedido_realizado", "pagamento_nao_autorizado",
            "pedido_com_falha", "send_purchase", "last_carrinho"
        ]);

        // TESTE AB - Teste do select de parcelas desativado
        $test_ab1 = request()->get('ab') == '1';

        return view('paginas.checkout.pagamento', compact(
            'carrinho',
            'url_logo',
            'cliente',
            'total_carrinho',
            'parcelamento',
            'is_mobile',
            'ultima_parcela',
            'e_pagamento_cartao',
            'canal_venda',
            'total_original',
            'total_carrinho_pix',
            'test_ab1'
        ));
    }

    /**
     * Parcelamento separado com juros e sem juros
     *
     * @param $total_carrinho
     * @param $canal_venda
     * @return array
     */
    private function parcelamento($total_carrinho, $canal_venda)
    {

        // Calcula o parcelamento
        $parcelamento = calculaParcelas(
            $total_carrinho,
            $canal_venda->juros_parcela,
            $canal_venda->parcelas_sem_juros,
            $canal_venda->maximo_parcelas
        );

        // Retorno
        $return = ['sem_juros' => [], 'com_juros' => [], 'parcelamento' => $parcelamento];

        // Separa as parcelas com juros e sem
        foreach ($parcelamento as $parcela) {
            if ($parcela['valor_juros'] > 0) {
                $return['com_juros'][] = $parcela;
                continue;
            }
            $return['sem_juros'][] = $parcela;
        }

        return $return;
    }

    /**
     * Página de falha no pagamento
     *
     * @return mixed
     */
    public function pagamentoNaoRealizado()
    {
        // Destino salvo na sessao
        $destino_atual = $this->getDestinoSession();

        // URL para a logo na navbar
        $url_logo = $destino_atual['url_destino'] ?? route('ecommerce.index');

        // Guarda as informações do pedido
        $info_falha = session()->get("pagamento_nao_autorizado");

        // Redirecionamento
        if (is_null($info_falha)) return redirect()->to($url_logo);

        // Salva o numero do pedido que falhou
        $numero_pedido = session()->get("pedido_com_falha");

        return view('paginas.checkout.falha-pagamento', compact(
            'info_falha',
            'numero_pedido',
            'url_logo'
        ));
    }

        /**
     * Página de expiração no pagamento
     *
     * @return mixed
     */
    public function pagamentoExpirado()
    {
        // Destino salvo na sessao
        $destino_atual = $this->getDestinoSession();

        // URL para a logo na navbar
        $url_logo = $destino_atual['url_destino'] ?? route('ecommerce.index');

        // Salva o numero do pedido que falhou
        $numero_pedido = session()->get("pedido_expirado")->codigo;

        return view('paginas.checkout.pagamento-expirado', compact(
            'numero_pedido',
            'url_logo'
        ));
    }

    /**
     * Página de pagamento realizado
     *
     * @return mixed
     */
    public function pagamentoRealizado()
    {
        // Destino salvo na sessao
        $destino_atual = $this->getDestinoSession();

        // Dados cliente
        $cliente = auth()->user();

        // URL para a logo na navbar
        $url_logo = $destino_atual['url_destino'] ?? route('ecommerce.index');

        // Dados do pedido realizado
        $pedido = session()->get('pedido_realizado');

        // Redirecionamento
        if (is_null($pedido)) return redirect()->to($url_logo);

        // Dados do ultimo carrinho
        $carrinho = session()->get('last_carrinho');

        // Pedido realizado com informacoes para trackers
        $pedido = ClienteService::pedidoTrackers($pedido->codigo, $carrinho, $cliente);

        // Controle para enviar o pedido para trackers
        $send_purchase = "false";

        // Evita que envie várias vezes
        if (!session()->has('send_purchase')) {
            $send_purchase = "true";
            session()->put('send_purchase', "true");
        }

        return view('paginas.checkout.pagamento-realizado', compact(
            'pedido',
            'url_logo',
            'send_purchase'
        ));
    }

    /**
     * @param PagamentoRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function efetuarPagamento(PagamentoRequest $request)
    {
        // Servicos no carrinho
        $carrinho = carrinho()->all();

        // Caso nao possua itens no carrinho
        if ($carrinho->count() == 0) {
            return back()->withErrors("Seu carrinho de compras está vazio!");
        }

        // Recupera os dados do canal de venda
        $canal_venda = CanalVenda::find(DefaultCacheService::getCanalVenda());

        // Adiciona um history
        $this->addHistory('Pedido recebido', $request->all());

        // Salva o IP do cliente
        $this->addHistory('IP Cliente: ' . getUserIP());

        // Verifica se o cliente está logado ou cria uma nova conta
        $cliente = $this->verifyUser($request, $canal_venda);

        // Prepara o array para gerar o pedido
        $array_pedido = PedidoService::prepareArrayPedido($carrinho, $cliente);

        // Salva o numero do pedido gerado para logs
        $this->numero_pedido = $array_pedido['codigo_pedido'];

        //--------- Aqui deverá verificar o metodo do pagamento para pix

        // Metodo de pagamento
        $metodo_pagamento = request()->get('metodo_pagamento');

        // Verifica de onde o cliente está acessando
        $origem = (new Agent())->isMobile() ? OrigemEnum::MOBILE : OrigemEnum::WEBSITE;

        // Pagamento em cartao
        if ($metodo_pagamento == MetodoPagamentoEnum::CARTAO_CREDITO) {

            // Adiciona um history
            $this->addHistory('Forma de pagamento em cartao de credito');

            // Dados do cartao de credito
            $dados_cartao = $request->get('credito');

            // Calcula o parcelamento
            $parcelamento = calculaParcelas(
                $array_pedido['valor_total'],
                $canal_venda->juros_parcela,
                $canal_venda->parcelas_sem_juros,
                $canal_venda->maximo_parcelas
            );

            // Recupera o juros da parcela selecionada
            $parcelamento = $parcelamento[$dados_cartao['parcelas']];

            // Efetua o pagamento e gera o pedido
            //$result = CartaoService::payCreditCardCielo($array_pedido, $cliente, $dados_cartao, $parcelamento);

            // Efetua o pagamento e gera o pedido
            $result = CartaoService::payCreditCardPagarme($array_pedido, $cliente, $dados_cartao, $parcelamento);

            // Historico
            $this->addHistory('Resultado pagamento', $result);

            // Caso deu tudo certo
            if ($result["approved"]) {

                // Parametro para o gerarPedidoCartao gerar  a reserva como aprovada
                $pagamento_autorizado = true;

                // Gera o pedido para o cliente como CREDITO
                $pedido = PedidoService::gerarPedidoCartao(
                    $array_pedido,
                    $result["dados_pagamento"],
                    $parcelamento['valor_juros'],
                    $cliente,
                    $canal_venda->id,
                    $origem,
                    $metodo_pagamento,
                    $pagamento_autorizado
                );

                // Verifica se o pedido ja esta finalizado
                // Caso não esteja, ele não envia os e-mails
                // Caso esteja, ele envia os e-mails para cliente e fornecedor
                // Caso for encontrada uma reserva não finalizada ele marca ela com uma FLAG
                if(FinalizacaoService::isPedidoFinalizado($pedido)) {
                    // Dispara o job de nova compra
                    NovaVendaJob::dispatch($pedido);
                }

                // Guarda as informações do pedido
                session()->put("pedido_realizado", $pedido);

                // Guarda os dados do carrinho para enviar para os trackers
                session()->put("last_carrinho", carrinho()->all());

                // Limpa o carrinho de compras
                carrinho()->destroy();

                // Redireciona para a página de sucesso
                return redirect()->route('ecommerce.carrinho.pagamento.sucesso');

            } else {

                // Parametro para o gerarPedidoCartao gerar  a reserva como negada
                $pagamento_autorizado = false;

                // Gera o pedido para o cliente como CREDITO
                $pedido = PedidoService::gerarPedidoCartao(
                    $array_pedido,
                    $result["dados_pagamento"],
                    $parcelamento['valor_juros'],
                    $cliente,
                    $canal_venda->id,
                    $origem,
                    $metodo_pagamento,
                    $pagamento_autorizado
                );

                // Guarda as informações do pedido
                session()->put("pagamento_nao_autorizado", $result);

                // Salva o numero do pedido que falhou
                session()->put("pedido_com_falha", $this->numero_pedido);

                // Redireciona para a página de sucesso
                return redirect()->route('ecommerce.carrinho.pagamento.falha');
            }
        }

        if ($metodo_pagamento == MetodoPagamentoEnum::PIX) {

            // Adiciona um history
            $this->addHistory('Forma de pagamento em PIX');

            $result = PixService::gerarPixPagarme($array_pedido, $cliente);

            // Historico
            $this->addHistory('Resultado pagamento', $result);

            // Gera o pedido para o cliente como PIX
            $pedido = PedidoService::gerarPedidoPix(
                $array_pedido,
                $result["dados_pagamento"],
                $cliente,
                $canal_venda->id,
                $origem,
            );

            // Guarda as informações do pedido
            session()->put("pedido_realizado", $pedido);

            // Guarda os dados do carrinho para enviar para os trackers
            session()->put("last_carrinho", carrinho()->all());

            // Limpa o carrinho de compras
            carrinho()->destroy();

            // Redireciona para a página de sucesso
            return redirect()->route('ecommerce.carrinho.pagamento.pix', $pedido->codigo);
        }

        // Adiciona um history
        $this->addHistory('Forma de pagamento inválida');

        return redirect()->back()->withErrors([
            "Pagamento" => "Método de pagamento inválido, tente novamente!"
        ]);
    }

    /**
     * Retorna a página de fazer o PIX
     * Antes ele verifica se o PIX já não foi pago, caso já tenha sido pago.
     *
    */
    public function pagamentoPix($pedido_id) {
        // Busca o pedido no banco
        $pedido = Pedido::where('codigo', $pedido_id)
                        ->with([
                            'reservas',
                            'transacaoPedido',
                            'reservas.servico',
                            'reservas.agendaDataServico',
                            'reservas.servico.fotoPrincipal',
                            'cliente'])
                        ->get()
                        ->first();

        // Caso o pedido seja inválido, retorna para o ecommerce
        if($pedido == null) {
            return redirect()->route('ecommerce.index');
        }

        // Caso o pedido não pertença ao usuário logado, retorna para o ecommerce
        if($pedido->cliente->id != auth()->user()->id) {
            return redirect()->route('ecommerce.index');
        }

        // Caso o pedido ja esteja pago, retorna o usuário para a página de pagamento concluido
        if($pedido->status == StatusPedidoEnum::PAGO) {
            return redirect()->route('ecommerce.carrinho.pagamento.sucesso');
        }

       // Faz a pequisa no gateway para entender se o PIX esta ou não pago.
       // Caso esteja pago, o próprio método ja seta o pedido e reservas como PAGO
       // Caso esteja expirado, o próprio método ja seta o pedido e reservas como expirado
       // Caso ainda esteja pendente, só retorna o status e nada mais
       $status_pix = PixService::getAndUpdateSituacaoPix($pedido);

       // Caso o PIX tenha sido pago
        if($status_pix == StatusPixEnum::PAGO) {
            // Retorna a página de pagamento autorizado
            return redirect()->route('ecommerce.carrinho.pagamento.sucesso');
        }

       // Caso o PIX tenha expirado
       if($status_pix == StatusPixEnum::EXPIRADO) {
            // Guarda o pedido que expirou na sessão
            session(['pedido_expirado' => $pedido]);

            // Retorna a página de pagamento expirado
            return redirect()->route('ecommerce.carrinho.pagamento.expirado');
       }

        // Caso o PIX ainda esteja pendente
        if($status_pix == StatusPixEnum::PENDENTE) {

            $transacao = $pedido->transacaoPedido->transacao->transacao->response->charges[0]->last_transaction;

            // Dados enviados para a página do QRCode
            $dados = [
                'pedido' => $pedido,
                'pix' => $transacao,
            ];

            // Retorna uma página com o QRCode para o pagamento
            return view('paginas.checkout.pagamento-pix', $dados);
        }
    }

    public function pagamentoPixJson(Request $request, $pedido_id) {

        // Busca o pedido no banco
        $pedido = Pedido::where('codigo', $pedido_id)
        ->with([
            'reservas',
            'transacaoPedido',
            'reservas.servico',
            'reservas.agendaDataServico',
            'reservas.servico.fotoPrincipal',
            'cliente'])
        ->get()
        ->first();

        // Faz a pequisa no gateway para entender se o PIX esta ou não pago.
        // Caso esteja pago, o próprio método ja seta o pedido e reservas como PAGO
        // Caso esteja expirado, o próprio método ja seta o pedido e reservas como expirado
        // Caso ainda esteja pendente, só retorna o status e nada mais
        $status_pix = PixService::getAndUpdateSituacaoPix($pedido);

        $dados = [
            'status' => ($status_pix != StatusPixEnum::PENDENTE) ? true : false
        ];

        return response($dados, 200);
    }

    /**
     * Cria uma nova conta ou retorna o cliente logado
     *
     * @param Request $request
     * @param $canal_venda
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    private function verifyUser(Request $request, $canal_venda)
    {
        // Caso o cliente não esteja logado cria uma nova conta
        if (auth()->check() == false) {

            // Adiciona um history
            $this->addHistory('Cliente comprou como convidado');

            // Cadastrar o cliente
            $novo_cliente = ClienteService::cadastrarCliente($request->get('cliente'), $canal_venda->id);

            // Envia email para o cliente com a nova senha
            Notification::send($novo_cliente['cliente'], new NovoClienteNotification($novo_cliente['password']));

            // Faz login do cliente
            auth()->login($novo_cliente['cliente']);

            return $novo_cliente['cliente'];
        }

        // Recupera os dados do cliente
        $cliente = auth()->user();

        // Atualiza os dados do cliente
        $cliente->update($request->get('cliente'));

        // Caso o cliente ainda não tenha endereço criado. Ele cria
        if($cliente->endereco == null) {
            $dados_cliente = $request->get('cliente');
            $dados_cliente['cliente_id'] = $cliente->id;
            $endereco = EnderecoCliente::create($dados_cliente);
        } else {

            // Caso ja tenha endereço criado. Ele atualiza.
            $cliente->endereco->update($request->get('cliente'));
        }

        return $cliente;
    }

    /**
     * Salva o processo da geração do pedido
     *
     * @param $mensagem
     * @param array $data
     */
    private function addHistory($mensagem, $data = [])
    {
        $mensagem = tirarAcentos($mensagem);

        // Mascara o numero do cartao
        if (isset($data['credito']['numero_cartao'])) {
            // Verificao pois no mobile os campos vem vazio mesmo sendo outra forma de pagamento
            if ($data['credito']['numero_cartao'] != "") {
                $data['credito']['numero_cartao'] = maskNumberCard($data['credito']['numero_cartao']);
                $data['credito']['codigo_cartao'] = "Safe_log";
            }
        }

        if (sizeof($data) > 0) {
            $this->history .= "[" . date('Y-m-d H:i:s') . "] - $mensagem " . json_encode($data) . "\r\n";
        } else {
            $this->history .= "[" . date('Y-m-d H:i:s') . "] - $mensagem \r\n";
        }
    }

    /**
     * Sava o log do pedido
     */
    public function __destruct()
    {
        // Dados do cookie
        $cookie_ga = $_COOKIE['_ga'] ?? "Cookie não encontrado";

        if ($this->history != "") {

            // Salva o cookie GA do google
            $this->history .= "[" . date('Y-m-d H:i:s') . "] - GA: $cookie_ga \r\n";

            // Salva o historio do pedido
            Storage::put("pedidos/" . $this->numero_pedido . ".txt", $this->history);
        }
    }

    /**
     * Salva os dados que o cliente informar no checkout na sessão
     * Alem de ajudar o cliente caso ele atualize a página, também
     * evita problemas com o código PIX caso o problema de atualizar a página aconteça.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function clienteSessao(Request $request) {
        session(['cliente' => $request->all()]);
        return response(['status' => true], 200);
    }
}
