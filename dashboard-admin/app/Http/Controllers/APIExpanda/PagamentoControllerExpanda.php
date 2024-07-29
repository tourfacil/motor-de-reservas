<?php

namespace App\Http\Controllers\APIExpanda;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use TourFacil\Core\Enum\MetodoPagamentoEnum;
use TourFacil\Core\Enum\OrigemEnum;
use TourFacil\Core\Models\CanalVenda;
use TourFacil\Core\Models\Cliente;
use TourFacil\Core\Models\Servico;
use TourFacil\Core\Models\VariacaoServico;
use TourFacil\Core\Services\AdminEcommerceAPI\AdminEcommerceAPI;
use TourFacil\Core\Services\Pagamento\CartaoService;
use TourFacil\Core\Services\PedidoService;

class PagamentoControllerExpanda extends Controller
{
    private $canal_venda_id = 1;
    private $req_invalida = [
        'info' => 'Requisição inválida',
        'status' => false,
    ];

    /**
     * Summary of efetuarVenda
     * Método que recebe a requisicao da API e gera a venda no Tour Fácil
     * @param Request $request
     * @return mixed
     */
    public function efetuarVenda(Request $request)
    {
        // Array para guardar os dados vindos do cliente
        $dados = [];

        // Try catch para transformar o json do cliente para Array em seguraca
        // Caso falhar, ele retorna bad request
        try {
            $dados = json_decode($request->get('data'), true);
        } catch (\Exception $e) {
            return response($this->req_invalida, 400);
        }

        // Caso nao seja informado um array de itens, ele retorna bad request
        if(is_array($dados) == false) {
            return response($this->req_invalida, 400);
        }
        
        // Valida os dados da requisicao
        $valido = $this->validarDadosReq($dados);

        // Caso a validacao falhar, retorna bad request
        if ($valido->fails()) {
            return response(['error' => $valido->errors()], 400);
        }

        // Pega os dados do pagamento
        $metodo_pagamento = $dados['metodo_pagamento'];

        // Pega o numero de parcelas
        $numero_parcelas = $dados['pagamento']['parcelamento'];

        // Converte a forma que a API recebe os dados para o formato dos services
        $servicos = $this->converterReq($dados['servicos']);

        // Converte a forma que a API recebe os dados para o formato dos services
        $cliente = $this->setCliente($dados['cliente']);

        // Busca o CanalVenda 1
        $canal_venda = CanalVenda::find($this->canal_venda_id);

        // Manda os dados para que se gere o array do pedido
        $array_pedido = PedidoService::prepareArrayPedido($servicos, $cliente);

        // Define a origem da venda como API
        $origem = OrigemEnum::API;

        // Caso o método de pagamento seja Cartão de crédito
        if($metodo_pagamento == MetodoPagamentoEnum::CARTAO_CREDITO) {

            // Converte a forma que a API recebe os dados para o formato dos services
            $dados_cartao = $this->convertCartao($dados['pagamento']);

            // Calcula o parcelamento
            $parcelamento = calculaParcelas(
                $array_pedido['valor_total'],
                $canal_venda->juros_parcela,
                $canal_venda->parcelas_sem_juros,
                $canal_venda->maximo_parcelas
            );

            // Busca o numero de parcelas
            $parcelamento = $parcelamento[$numero_parcelas];

            // Faz a requisicao para a pagarme
            $result = CartaoService::payCreditCardPagarme($array_pedido, $cliente, $dados_cartao, $parcelamento);

            // Caso for aprovada
            if($result['approved']) {

                // Variavel de controle para o pedido vir autorizado
                $pagamento_autorizado = true;

                // Registra o pedido 
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

                // Solicita que o ecommerce envie os e-mails e integre, caso necessario
                AdminEcommerceAPI::solicitarEnvioDeEmailAposVendaInterna($pedido);

                // Retorna a resposta de pagamento aprovado
                return response([
                    'info' => 'APROVADO',
                    'info2' => 'Pedido criado com sucesso',
                    'numero_pedido' => $pedido->codigo,  
                    'status' => true,
                ]);
            } else {

                // Variavel de controle para o pedido vir negado
                $pagamento_autorizado = false;

                // Gera o pedido negado
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

                // Retorna a resposta de pedido negado
                return response([
                    'info' => 'NÃO APROVADO',
                    'info2' => 'Não foi possivel gerar o pedido - Não haverá cobrança',
                    'status' => false,
                ]);
            }
        } else {
            // Caso o metodo de pagamento seja desconhecido, retorna bad request
            return response($this->req_invalida, 400);
        }
    } 

    /**
     * 
     * Método responsavel por converter os dados enviados a API para os Services do Tour Facil
     * @param mixed $dados
     * @return array
     */
    private function converterReq($dados) {

        $retorno = [];

        foreach($dados as $index => $servico) {
            $variacoes = $this->getQuantidadesVariacoes($servico['clientes']);
            $quantidades_bloqueios = $this->getQuantidadesComESemBloqueios($variacoes);
            $foto_principal = $this->getFotoPrincipal($servico['uuid']);
            $quantidade_total = $quantidades_bloqueios['com_bloqueio'] + $quantidades_bloqueios['sem_bloqueio'];
            $clientes = $this->setVariacaoIDOnClientes($servico['clientes']);
            $adicionais = $this->setCampoIDOnCampos($servico['campos_adicionais']);

            $retorno[$index]['uuid'] = $servico['uuid'];
            $retorno[$index]['agenda_selecionada']['data_servico_id'] = $servico['codigo_data'];
            $retorno[$index]['agenda_selecionada']['variacoes'] = $variacoes;
            $retorno[$index]['foto_principal'] = $foto_principal;
            $retorno[$index]['quantidade'] = $quantidade_total;
            $retorno[$index]['com_bloqueio'] = $quantidades_bloqueios['com_bloqueio'];
            $retorno[$index]['sem_bloqueio'] = $quantidades_bloqueios['sem_bloqueio'];
            $retorno[$index]['acompanhantes'] = $clientes;
            $retorno[$index]['adicionais'] = $adicionais;
        }

        return $retorno;
    }

    /**
     * Summary of getQuantidadesVariacoes
     * Obtem as quantidades de cada categoria de idade
     * Necessario porque na api o integrador só manda o array de clientes, então precisamos fazer a contagem
     * @param array $clientes
     * @return array
     */
    private function getQuantidadesVariacoes(Array $clientes) {

        $variacoes = [];

        foreach($clientes as $cliente) {
            if(isset($variacoes[$cliente['codigo_categoria']])) {
                $variacoes[$cliente['codigo_categoria']]['quantidade']++;
            } else {
                $variacoes[$cliente['codigo_categoria']]['quantidade'] = 1;
            }
            $variacoes[$cliente['codigo_categoria']]['variacao_id'] = $cliente['codigo_categoria'];
        }

        return $variacoes;
    }

    /**
     * Summary of getQuantidadesComESemBloqueios
     * Conta quantos clientes consomem bloqueio e quantos não consomem bloqueio
     * Necessarios porque o integrador informa os clientes e a categoria, entao precisamos pesquisar no banco...
     * se aquele categoria consome ou nao o bloqueio
     * @param array $variacoes
     * @return array
     */
    private function getQuantidadesComESemBloqueios(Array $variacoes)
    {
        $quantidades = [
            'com_bloqueio' => 0,
            'sem_bloqueio' => 0,
        ];

        foreach($variacoes as $variacao) {

            $variacao_db = VariacaoServico::find($variacao['variacao_id']);

            if($variacao_db->consome_bloqueio == 'SIM') {
                $quantidades['com_bloqueio'] += $variacao['quantidade'];
            } else {
                $quantidades['sem_bloqueio'] += $variacao['quantidade'];
            }
        }

        return $quantidades;
    }

    /**
     * Summary of getFotoPrincipal
     * Busca a foto principal do servico
     * @param mixed $uuid
     * @return string
     */
    private function getFotoPrincipal($uuid)
    {
        $servico = Servico::where('uuid', $uuid)->get()->first();

        return env('CDN_URL') . $servico->fotoPrincipal->foto['MEDIUM'];
    }

    /**
     * Summary of setVariacaoIDOnClientes
     * Seta o variacao_id no array vindo do integrador
     * Necessario pq nao mostramos o nome das nossas variaveis internas do banco, entao passamos um nome ficticio,
     * chamado codigo_categoria que precisa ser convertido para variacao_servico_id
     * @param mixed $clientes
     * @return array<array>|null
     */
    private function setVariacaoIDOnClientes($clientes) {

        $retorno = [];

        foreach($clientes as $cliente) {

            $retorno[] = [
                'nome' => $cliente['nome'],
                'documento' => $cliente['documento'],
                'nascimento' => $cliente['nascimento'],
                'variacao_servico_id' => $cliente['codigo_categoria'],
            ];
        }

        if(count($retorno) == 0) {
            return null;
        }

        return $retorno;
    }

    /**
     * Summary of setCampoIDOnCampos
     * Seta o campo_adicional_servico_id no array vindo do integrador
     * Necessario pq nao mostramos o nome das nossas variaveis internas do banco, entao passamos um nome ficticio,
     * chamado codigo_campo_adicional que precisa ser convertido para campo_adicional_servico_id
     * @param mixed $clientes
     * @return array<array>|null
     */
    private function setCampoIDOnCampos($campos) {

        $retorno = [];

        foreach($campos as $campo) {
            $retorno[] = [
                'campo_adicional_servico_id' => $campo['codigo_campo_adicional'],
                'informacao' => $campo['campo']
            ];
        }

        if(count($retorno) == 0) {
            return null;
        }

        return $retorno;
    }

    /**
     * Summary of convertCartao
     * Converte os dados informados na API para o formato que nossos services usam
     * @param mixed $pagamento
     * @return array
     */
    private function convertCartao($pagamento) {

        return [
            'nome_cartao' => $pagamento['cartao']['nome'],
            'numero_cartao' => $pagamento['cartao']['numero'],
            'bandeira_cartao' => $pagamento['cartao']['bandeira'],
            'validade_mes_cartao' => $pagamento['cartao']['validade_mes'],
            'validade_ano_cartao' => $pagamento['cartao']['validade_ano'],
            'codigo_cartao' => $pagamento['cartao']['cvv'],
            'parcelas' => $pagamento['parcelamento'],
        ];
    }

    /**
     * Summary of setCliente
     * Verifica se o cliente existe, e caso sim, retorna-o, se não, cria-o
     * @param array $cliente
     * @return mixed
     */
    private function setCliente(Array $cliente) {

        $cliente_db = Cliente::where('email', $cliente['email'])
        ->with('endereco')
        ->get()
        ->first();


        if($cliente_db) {

            if($cliente_db->endereco == null) {
                $cliente['cliente_id'] = $cliente_db->id;
                $endereco = $cliente_db->endereco()->create($cliente);
            }

            return $cliente_db;

        }

        $cliente['canal_venda_id'] = $this->canal_venda_id;
        $cliente['password'] = bcrypt($cliente['cpf']);
        $cliente_db = Cliente::create($cliente);
        $cliente_db->endereco()->create($cliente);

        return $cliente_db;
    }

    /**
     * Summary of validarDadosReq
     * Validacao dos dados da requisicao
     * @param array $dados
     * @return \Illuminate\Validation\Validator
     */
    private function validarDadosReq(Array $dados) {

        $validator = Validator::make($dados, [
            'metodo_pagamento' => 'required|string|min:3|max:10',
            
            'pagamento.cartao.numero' => 'required|string|size:16',
            'pagamento.cartao.nome' => 'required|string|max:255',
            'pagamento.cartao.cvv' => 'required|string|min:2|max:5',
            'pagamento.cartao.validade_ano' => 'required|string',
            'pagamento.cartao.validade_mes' => 'required|string',
            'pagamento.cartao.bandeira' => 'required|string|min:2|max:10',

            'pagamento.parcelamento' => 'integer|required|min:1|max:12',

            'cliente.nome' => 'required|string|min:5|max:50',
            'cliente.email' => 'required|email|min:5|max:30',
            'cliente.cpf' => 'required|string|size:11',
            'cliente.nascimento' => 'required|string|size:10',
            'cliente.telefone' => 'required|string|min:8|max:16',
            'cliente.rua' => 'required|string|min:5|max:50',
            'cliente.numero' => 'required|string|min:2|max:30',
            'cliente.bairro' => 'required|string|min:5|max:30',
            'cliente.cidade' => 'required|string|min:2|max:30',
            'cliente.estado' => 'required|string|size:2',
            'cliente.cep' => 'required|string|size:9',

            'servicos.*.uuid' => 'required|uuid',
            'servicos.*.codigo_data' => 'required|integer',

            'servicos.*.clientes' => 'array|nullable',
            'servicos.*.clientes.*.codigo_categoria' => 'integer',
            'servicos.*.clientes.*.nome' => 'string|max:50',
            'servicos.*.clientes.*.documento' => 'string|size:11',
            'servicos.*.clientes.*.nascimento' => 'string|size:10',

            'servicos.*.campos_adicionais' => 'array|nullable',
            'servicos.*.campos_adicionais.*.codigo_campo_adicional' => 'required|integer',
            'servicos.*.campos_adicionais.*.campo' => 'required|string|min:3|max:30',
        ]);

        return $validator;
    }
}
