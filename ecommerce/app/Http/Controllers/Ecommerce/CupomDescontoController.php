<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use TourFacil\Core\Services\CupomDescontoService;
use TourFacil\Core\Services\DescontoService;
use TourFacil\Core\Services\Pagamento\Gerencianet\Pix\PixService;

class CupomDescontoController extends Controller
{
    public function adicionarCupom(Request $request) {

        // Caso tenha um PIX pendente na sessão. Ele destroi para evitar problemas
        PixService::cancelarPixSessao();

        // Recupera o código do cupom do POST
        $codigo = $request->get('codigo_cupom');

        // Caso o código informado seja 999, o cupom será removido da sessão
        if($codigo == "999") {
            // Remove o cupom da sessão
            CupomDescontoService::removerCupomSessao();

            return response(['info' => "O cupom foi removido", 'status' => true], 200);
        }

        // Busca todos os serviços do carrinho que tem desconto do sistema de promoções ativo
        $servicos_com_desconto = DescontoService::getServicosComDescontoCarrinho();

        // Caso tenha algum, não permite o uso do cupom, pois não é cumulativo
        if($servicos_com_desconto) {

            // Monta uma mensagem para o cliente
            $mensagem = "Não é possivel aplicar o cupom de desconto, pois os seguintes serviços já estão com valor promocional: ";

            // Roda os serviços para montar a mensagem com o nome dos serviços que não podem usar desconto
            foreach($servicos_com_desconto as $servico_desconto) {
                $mensagem .= "{$servico_desconto['nome']}, ";
            }

            // Finaliza a mensagem removido o ultimo espaço e virgula
            $mensagem = substr($mensagem, 0, -2);

            // Retorna
            return response(['info' => $mensagem, 'status' => false], 200);
        }

        // Busca um cupom válido baseado nas regras descritas no PHPDoc do seguinte método
        $cupom_valido = CupomDescontoService::getCupomValidoByCodigo($codigo);

        // Caso não seja encontrado cupom, enviado resposta de erro ao cliente
        if($cupom_valido == null) {
            return response(['info' => "O cupom não foi encontrado ou não é mais válido", 'status' => false], 200);
        }

        // Aplica o cupom de desconto na sessão
        // Caso for um cupom de serviço especifico, também coloca coloca a informação no carrinho
        CupomDescontoService::aplicarCupomNaSessao($cupom_valido);

        // Retorna ao cliente avisando que o cupom foi aplicado
        return response(['info' => "O cupom \"$cupom_valido->nome_publico\" foi aplicado com sucesso", 'status' => true], 200);
    }

    public function removerCupom(Request $request) {

        // Caso tenha um PIX pendente na sessão. Ele destroi para evitar problemas
        PixService::cancelarPixSessao();

        // Remove o cupom da sessão caso tenha
        CupomDescontoService::removerCupomSessao();

        return response(['success' => true], 200);
    }
}
