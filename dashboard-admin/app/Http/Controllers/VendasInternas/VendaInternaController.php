<?php

namespace App\Http\Controllers\VendasInternas;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use TourFacil\Core\Models\Servico;
use TourFacil\Core\Models\CanalVenda;
use TourFacil\Core\Models\Cliente;
use TourFacil\Core\Models\VendaInternaLink;
use TourFacil\Core\Services\CupomDescontoService;
use TourFacil\Core\Services\PedidoService;
use Illuminate\Support\Str;
use TourFacil\Core\Enum\MetodoPagamentoEnum;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Jobs\NovaVendaJob;
use TourFacil\Core\Enum\MeioPagamentoInternoEnum;
use TourFacil\Core\Enum\MetodoPagamentoInternoEnum;
use TourFacil\Core\Services\AdminEcommerceAPI\AdminEcommerceAPI;

/**
 *
 * Vendas internas
 * Podem ser pagas ou pendentes
 * Nas pagas a reserva e lançada diretamente no sistema
 * Nas pendentes é gerado um link com a reserva selecionada que depois é enviado para o cliente pagar pelo ecommerce
 *
 */
class VendaInternaController extends Controller
{
    /**
     * Retorna a index de vendas internas
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function index(Request $request) {

        $servicos = Servico::where('status', 'ATIVO')->get();
        $meios_pagamento = MeioPagamentoInternoEnum::MEIOS;
        $metodos_pagamento = MetodoPagamentoInternoEnum::METODOS;

        $dados = [
            'servicos' => $servicos,
            'meios_pagamento' => $meios_pagamento,
            'metodos_pagamento' => $metodos_pagamento,
        ];

        return view('paginas.vendas_internas.vendas_internas', $dados);
    }

    /**
     * Pega um servico_id e retorna em um array formatado identico ao que é usado no carrinho do ecommerce
     * Usado para garantir que não hajam bugs no processo de checkout que é igual ao do ecommerce
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Foundation\Application|\Illuminate\Http\Response
     */
    public function obterServicoEstiloCarrinho(Request $request) {

        $servico_id = $request->get('servico_id');

        $servico = Servico::find($servico_id);

        $dados = [
            'servico' => $servico,
        ];

        return response($dados, 200);
    }

    /**
     * Função de adicionar serviço ao carrinho
     * Serve para vendas internas pagas (Geração de reserva sem pagamento) e geração de links de vendedoras
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Foundation\Application|\Illuminate\Http\Response
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function carrinhoAdicionar(Request $request) {

        // Remove o cupom inserido por segurança contra BUGS
        if(CupomDescontoService::isCupomNaSessao()) {
            CupomDescontoService::removerCupomSessao();
        }

        // Busca os dados da requisição
        $dados = $request->all();

        // Caso a venda interna for do tipo pago
        if($dados['tipo_pagamento'] == "PAGO" || session()->get('carrinho_tipo_pagamento') == "PAGO") {

            // Busca o serviço no banco de dados;
            $servico = Servico::find($dados['servico']['id']);

            // Salva as variações informadas pelo user e inicia variaveis de contagem
            $variacoes = $dados['variacoes'];
            $consome_bloqueio = 0;
            $nao_consome_bloqueio = 0;
            $valor = 0;

            // Roda todas as variações
            foreach($variacoes as $variacao) {

                // Caso a variavel tenha quantidade nos atributos, quer dizer que foi selecionada pelo user
                // Contabiliza as quantidades totais dessa variação
                if(array_key_exists('quantidade', $variacao)) {
                    $valor += $variacao['quantidade'] * $variacao['valor_venda'];
                }

                // Caso a variaçao consuma bloqueio, contabiliza na quantidade de bloqueios gasta
                if($variacao['bloqueio'] == "SIM") {

                    if(array_key_exists('quantidade', $variacao)) {
                        $consome_bloqueio += $variacao['quantidade'];
                    }
                } else {

                    if(array_key_exists('quantidade', $variacao)) {
                        $nao_consome_bloqueio += $variacao['quantidade'];
                    }
                }
            }

            // Monta o serviço no mesmo estilo de carrinho do ecommerce
            $servico_carrinho = [
                'gtin' => $dados['servico']['id'],
                'uuid' => $dados['servico']['uuid'],
                'nome_servico' => $dados['servico']['nome'],
                'integracao' => $dados['servico']['integracao'],
                'foto_principal' => env('ECOMMERCE_CDN_URL') . $servico->fotoPrincipal->foto["LARGE"],
                'categoria' => $servico->categorias->first()->nome,
                'destino' => $servico->destino->nome,
                'url' => "",
                'cidade' => $dados['servico']['cidade'],
                'localizacao' => $dados['servico']['localizacao'],
                'horario' => $servico->horario,
                'com_bloqueio' => $consome_bloqueio,
                'sem_bloqueio' => $nao_consome_bloqueio,
                'valor_total' => $valor,
                'agenda_selecionada' => $dados['agenda'],
                'acompanhantes' => $dados['acompanhantes'],
                'adicionais' => $dados['campos_adicionais'],
            ];

            // Seta o tipo de pagamento que esta sendo usado. Para depois continuar a montagem do carrinho no mesmo modo
            if(session()->exists('carrinho_tipo_pagamento') == false) {
                session(['carrinho_tipo_pagamento' => $dados['tipo_pagamento']]);
            }

            // Salva o carrinho
            session()->push('carrinho', $servico_carrinho);

            // Salva o titular
            session(['carrinho_titular' => $dados['titular_pedido']]);

            // Salva os dados do pagamento
            session(['carrinho_pagamento' => $dados['pagamento']]);
        } else {

            // Caso o tipo de venda interna seja pendente (Geração de link de vendedora)

            // Busca o serviço no banco
            $servico = Servico::find($dados['servico']['id']);

            // Salva as variações informadas pelo user e inicia variaveis de contagem
            $variacoes = $dados['variacoes'];
            $consome_bloqueio = 0;
            $nao_consome_bloqueio = 0;
            $valor = 0;

            // Roda todas as variações
            foreach($variacoes as $variacao) {

                if(array_key_exists('quantidade', $variacao)) {
                    $valor += $variacao['quantidade'] * $variacao['valor_venda'];
                }

                if($variacao['bloqueio'] == "SIM") {

                    if(array_key_exists('quantidade', $variacao)) {
                        $consome_bloqueio += $variacao['quantidade'];
                    }

                } else {

                    if(array_key_exists('quantidade', $variacao)) {
                        $nao_consome_bloqueio += $variacao['quantidade'];
                    }
                }
            }

            // Monta o carrinho
            $servico_carrinho = [
                'gtin' => $dados['servico']['id'],
                'uuid' => $dados['servico']['uuid'],
                'nome_servico' => $dados['servico']['nome'],
                'integracao' => $dados['servico']['integracao'],
                'foto_principal' => env('ECOMMERCE_CDN_URL') . $servico->fotoPrincipal->foto["LARGE"],
                'categoria' => $servico->categorias->first()->nome,
                'destino' => $servico->destino->nome,
                'url' => "",
                'cidade' => $dados['servico']['cidade'],
                'localizacao' => $dados['servico']['localizacao'],
                'horario' => $servico->horario,
                'com_bloqueio' => $consome_bloqueio,
                'sem_bloqueio' => $nao_consome_bloqueio,
                'valor_total' => $valor,
                'agenda_selecionada' => $dados['agenda'],
                'cadastro_pendente' => true,
                'variacoes' => $dados['variacoes'],
            ];

            if(session()->exists('carrinho_tipo_pagamento') == false) {
                session(['carrinho_tipo_pagamento' => $dados['tipo_pagamento']]);
            }

            session()->push('carrinho', $servico_carrinho);
        }

        return response(['req' => true], 200);
    }

    /**
     * Função para remover um serviço do carrinho
     *
     * @param Request $request
     * @param $index
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function carrinhoRemover(Request $request, $index) {

        // Remove o cupom inserido por segurança contra BUGS
        if(CupomDescontoService::isCupomNaSessao()) {
            CupomDescontoService::removerCupomSessao();
        }

        session()->forget("carrinho.$index");

        if(session()->get('carrinho') == []) {
            session()->forget('carrinho_tipo_pagamento');
        }

        return redirect()->route('app.venda-interna.index');
    }

    /**
     * Sessão para finalizar o carrinho
     * É para casos de venda interna paga. Nesse momento ele gera a reserva e o pedido
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function carrinhoFinalizar(Request $request) {

        // Servicos no carrinho
        $carrinho = session()->get('carrinho');

        // Caso nao possua itens no carrinho
        if (count($carrinho) == 0) {
            return back()->withErrors("Seu carrinho de compras está vazio!");
        }

        // Recupera os dados do canal de venda
        $canal_venda = CanalVenda::find(1);

        // Adiciona um history
        //$this->addHistory('Pedido recebido', $request->all());

        // Salva o IP do cliente
        //$this->addHistory('IP Cliente: ' . getUserIP());

        // Verifica se o cliente está logado ou cria uma nova conta

        $email_cliente = session()->get('carrinho_titular.email');

        $cliente = Cliente::where('email', $email_cliente)->get()->first();


        if($cliente == null) {

            $cliente = session()->get('carrinho_titular');

            $nascimento = dateStringBRParaDate($cliente['nascimento']);

            // Seta o CPF como senha. Removendo os caracteres especiais
            $senha = str_replace(".", "", $cliente['documento']);
            $senha = str_replace("-", "", $senha);
            $senha = Hash::make($senha);

            $cliente = Cliente::create([
                'canal_venda_id' => 1,
                'uuid' => (string) Str::uuid(),
                'nome' => $cliente['nome'],
                'email' => $cliente['email'],
                'cpf' => $cliente['documento'],
                'nascimento' => $nascimento->format('d/m/Y'),
                'telefone' => $cliente['telefone'],
                'password' => $senha,
            ]);
        }

        //$cliente = $this->verifyUser($request, $canal_venda);

        // Prepara o array para gerar o pedido
        $array_pedido = PedidoService::prepareArrayPedido($carrinho, $cliente);

        // Salva o numero do pedido gerado para logs
        $this->numero_pedido = $array_pedido['codigo_pedido'];

        // Metodo de pagamento
        $metodo_pagamento = "INTERNO";

        // Verifica de onde o cliente está acessando
        $origem = "TERMINAL";

        if ($metodo_pagamento == MetodoPagamentoEnum::INTERNO) {

            // Gera o pedido para o cliente como interno
            $pedido = PedidoService::gerarPedidoInterno(
                $array_pedido,
                $cliente,
                $canal_venda->id,
                $origem,
                $metodo_pagamento,
                session()->get('carrinho_pagamento'),
            );

            // Dispara o job de nova compra
            AdminEcommerceAPI::solicitarEnvioDeEmailAposVendaInterna($pedido);

            // Limpa o carrinho de compras
            session()->forget('carrinho');

            session()->forget('carrinho_tipo_pagamento');

            session()->forget('carrinho_titular');

            session()->forget('carrinho_pagamento');

            // Redireciona para a página de sucesso
            return redirect()->route('app.venda-interna.index');
        }

        // Adiciona um history
        //$this->addHistory('Forma de pagamento inválida');

        return redirect()->back()->withErrors([
            "Pagamento" => "Método de pagamento inválido, tente novamente!"
        ]);

    }

    /**
     * Serve para as vendas internas pendentes.
     * Nesse momento ele gera um link que a vendedora enviará ao cliente
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function gerarLink(Request $request) {

        $carrinho = session()->get('carrinho');
        $user = auth()->user();

        $venda_interna_link = [
            'user_id' => $user->id,
            'afiliado_id' => $user->afiliado_id,
            'vendedor_id' => $user->vendedor_id,
            'carrinho' => json_encode($carrinho),
            'uuid' => (string) Str::uuid(),
        ];

        $uuid = $venda_interna_link['uuid'];

        $link = env("ECOMMERCE_URL") . "/carrinho-de-compras/link/$uuid";

        session(['carrinho_link' => $link]);

        $venda_interna_link = VendaInternaLink::create($venda_interna_link);
        return redirect()->route('app.venda-interna.index');
    }

    /**
     * Serve para iniciar um novo carrinho para gerar novos links após a geração de um link
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function novoLink(Request $request) {
        session()->forget('carrinho_link');
        session()->forget('carrinho_titular');
        session()->forget('carrinho_tipo_pagamento');
        session()->forget('carrinho');

        return redirect()->route('app.venda-interna.index');
    }

    /**
     * Serve para consultar o banco de dados e verificar se aquele e-mail de titular ja existe.
     * Caso exista ele retorna os dados para o auto complete no front-end
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Foundation\Application|\Illuminate\Http\Response
     */
    public function consultarEmail(Request $request) {
        $email = $request->get('email');

        $cliente = Cliente::where('email', $email)->get()->first();

        return response(['cliente' => $cliente], 200);
    }

    /**
     * Serve para pesquisar um cupom de desconto.
     * Caso ele encontre o mesmo ja será setado na sessão
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Foundation\Application|\Illuminate\Http\Response
     */
    public function setCupomDesconto(Request $request) {

        $codigo = $request->get('cupom');

        $cupom = CupomDescontoService::getCupomValidoByCodigo($codigo);

        if($cupom == null) {
            return response(['status' => false], 200);
        }

        CupomDescontoService::aplicarCupomNaSessao($cupom);

        return response(['status' => true, 'cupom' => $cupom], 200);
    }

    /**
     * Serve para remover o cupom de desconto da sessão caso exista
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Foundation\Application|\Illuminate\Http\Response
     */
    public function unsetCupomDesconto(Request $request) {

        CupomDescontoService::removerCupomSessao();

        return response(['status' => true],200);
    }
}
