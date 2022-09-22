<?php 

namespace App\Services;

use TourFacil\Core\Models\AgendaDataServico;
use TourFacil\Core\Models\VariacaoServico;
use TourFacil\Core\Models\Servico;


abstract class ConferenciaReserva 
{
    // Método resposanvel por garantir que o produto do carrinho esteja com o valor atualizado e
    // que ainda tenham vagas disponiveis 
    public static function confereValorEVagasDaSessao() {

        $carrinho = carrinho()->all();

        $resposta = [];

        foreach($carrinho as $servico_sessao) {

            // Recupera o serviço e sua agenda direto do banco de dados para garantir que as informações estejam atualizadas

            $servico_db = Servico::find($servico_sessao['gtin']);
            $agenda_servico_db = AgendaDataServico::find($servico_sessao['agenda_selecionada']['data_servico_id']);

            // Guarda algumas informações que podem ser uteis para quem recer a resposta

            $resposta[$servico_db->id]['info']['servico_db'] = $servico_db;
            $resposta[$servico_db->id]['info']['agenda_servido_db'] = $agenda_servico_db;


            // Verifica se o produto ainda tem disponibilidade 

            $quantidade_disponivel_db = $agenda_servico_db->disponivel - $agenda_servico_db->consumido;

            // Roda todas os clientes para ver quantos clientes que consomem bloqueio estão no passeio

            $quantidade_clientes_sessao = 0;

            foreach($servico_sessao['acompanhantes'] as $pessoa_sessao) {
                $variacao_servico = VariacaoServico::find($pessoa_sessao['variacao_servico_id']);

                if($variacao_servico->consome_bloqueio == "SIM") {
                    $quantidade_clientes_sessao++;
                }
            }

            if(($quantidade_disponivel_db - $quantidade_clientes_sessao) < 0) {
                $resposta[$servico_db->id]['falta_vagas'] = true;
            } else {
                $resposta[$servico_db->id]['falta_vagas'] = false;
            }

            // Verifica se o produto esta com o valor atualizado

            $valor_venda_sessao = $servico_sessao['agenda_selecionada']['valor_venda'];
            $valor_venda_db = $agenda_servico_db->valor_venda;
            
            if($valor_venda_sessao != $valor_venda_db) {
                $resposta[$servico_db->id]['valor_diferente'] = true;
            } else {
                $resposta[$servico_db->id]['valor_diferente'] = false;
            }
        }

        return $resposta;
    }
}
